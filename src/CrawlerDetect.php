<?php

/*
 * This file is part of Crawler Detect - the web crawler detection library.
 *
 * (c) Mark Beech <m@rkbee.ch>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jaybizzle\CrawlerDetect;

use Jaybizzle\CrawlerDetect\Fixtures\AbstractProvider;
use Jaybizzle\CrawlerDetect\Fixtures\Crawlers;
use Jaybizzle\CrawlerDetect\Fixtures\Exclusions;
use Jaybizzle\CrawlerDetect\Fixtures\Headers;

class CrawlerDetect
{
    /**
     * The user agent.
     *
     * @var string|null
     */
    protected $userAgent;

    /**
     * Headers that contain a user agent.
     *
     * @var array
     */
    protected $httpHeaders = [];

    /**
     * Store regex matches.
     *
     * @var array
     */
    protected $matches = [];

    /**
     * Crawlers object.
     *
     * @var \Jaybizzle\CrawlerDetect\Fixtures\Crawlers
     */
    protected $crawlers;

    /**
     * Exclusions object.
     *
     * @var \Jaybizzle\CrawlerDetect\Fixtures\Exclusions
     */
    protected $exclusions;

    /**
     * Headers object.
     *
     * @var \Jaybizzle\CrawlerDetect\Fixtures\Headers
     */
    protected $uaHttpHeaders;

    /**
     * The compiled regex string.
     *
     * @var string
     */
    protected $compiledRegex;

    /**
     * The compiled exclusions regex string.
     *
     * @var string
     */
    protected $compiledExclusions;

    /**
     * Cache of compiled regex strings keyed by fixture class name, shared
     * across instances so per-request `new CrawlerDetect` calls don't
     * re-implode the (~1500-entry) pattern list each time.
     *
     * @var array<string, string>
     */
    protected static $compileCache = [];

    /**
     * Class constructor.
     */
    public function __construct(?array $headers = null, $userAgent = null)
    {
        $this->crawlers = new Crawlers;
        $this->exclusions = new Exclusions;
        $this->uaHttpHeaders = new Headers;

        $this->compiledRegex = $this->compileFixtureRegex($this->crawlers);
        $this->compiledExclusions = $this->compileFixtureRegex($this->exclusions);

        $this->setHttpHeaders($headers);
        $this->setUserAgent($userAgent);
    }

    /**
     * Compile and memoize the regex for a fixture provider.
     *
     * The pattern list is a fixed class property, so the class name is a
     * sufficient cache key — cheaper than hashing the patterns themselves.
     *
     * @return string
     */
    protected function compileFixtureRegex(AbstractProvider $fixture)
    {
        $class = get_class($fixture);

        if (! isset(self::$compileCache[$class])) {
            self::$compileCache[$class] = $this->compileRegex($fixture->getAll());
        }

        return self::$compileCache[$class];
    }

    /**
     * Compile the regex patterns into one regex string.
     *
     * A non-capturing group is used because callers only need the full
     * match (preg_match's $matches[0]), not a back-reference.
     *
     * @param  array  $patterns
     * @return string
     */
    public function compileRegex($patterns)
    {
        return '(?:'.implode('|', array_map([$this, 'boundOpenPrefix'], $patterns)).')';
    }

    /**
     * Stop a pattern with an open-ended prefix from being retried at every
     * character of the same token.
     *
     * The catch-all starts with '[a-z0-9\-_]*' so that getMatches() returns
     * the whole name ('somenaughtybot') and not just the keyword. Unanchored,
     * the engine attempts it from each position in a run of those characters
     * and rescans the rest of the run every time, which is quadratic in the
     * length of the run. Without the PCRE JIT a few kilobytes of filler in a
     * header costs seconds.
     *
     * Requiring that the prefix starts at the beginning of a token makes it
     * linear. The leftmost match was always at the start of the token anyway,
     * so results and getMatches() are unchanged.
     *
     * This is applied here and not in the fixture because the exported
     * patterns are used by ports whose regex engines have no lookbehind.
     *
     * @param  string  $pattern
     * @return string
     */
    protected function boundOpenPrefix($pattern)
    {
        if (strpos($pattern, '[a-z0-9\-_]*') === 0) {
            return '(?<![a-z0-9\-_])'.$pattern;
        }

        return $pattern;
    }

    /**
     * Set HTTP headers.
     *
     * Accepts either the _SERVER-style names PHP's SAPI produces
     * (HTTP_USER_AGENT) or the real header names carried by a PSR-7 request,
     * HttpFoundation, Swoole or a Lambda event ('User-Agent'). Both are stored
     * under the canonical HTTP_ form so lookups stay uniform.
     *
     * @param  array|null  $httpHeaders
     */
    public function setHttpHeaders($httpHeaders = null)
    {
        // Use global _SERVER if $httpHeaders aren't defined.
        if (! is_array($httpHeaders) || ! count($httpHeaders)) {
            $httpHeaders = $_SERVER;
        }

        // Clear existing headers.
        $this->httpHeaders = [];

        $uaHeaders = array_flip(
            array_map([$this, 'normaliseHeaderName'], $this->getUaHttpHeaders())
        );

        // Only save HTTP headers. In PHP land, that means _SERVER vars that
        // start with HTTP_ — plus, for non-SAPI sources, any key whose real
        // header name is one we actually read.
        foreach ($httpHeaders as $key => $value) {
            $name = $this->normaliseHeaderName($key);

            if (strpos(strtoupper((string) $key), 'HTTP_') === 0 || isset($uaHeaders[$name])) {
                $this->httpHeaders['HTTP_'.$name] = $this->normaliseHeaderValue($value);
            }
        }
    }

    /**
     * Reduce a header name to its canonical form - uppercased, underscore
     * separated, with any SAPI 'HTTP_' prefix removed.
     *
     * @param  string  $key
     * @return string
     */
    protected function normaliseHeaderName($key)
    {
        $key = strtoupper((string) $key);

        // Strip the prefix before folding hyphens, so only a genuine
        // underscore-separated SAPI key loses it. A real header named
        // 'Http-User-Agent' must not be mistaken for the user agent.
        if (strpos($key, 'HTTP_') === 0) {
            $key = substr($key, 5);
        }

        return str_replace('-', '_', $key);
    }

    /**
     * Flatten a header value. PSR-7 and HttpFoundation both expose values as
     * an array of strings, where the SAPI gives a single pre-joined string.
     *
     * @param  mixed  $value
     * @return string
     */
    protected function normaliseHeaderValue($value)
    {
        return is_array($value) ? implode(', ', $value) : (string) $value;
    }

    /**
     * Return user agent headers.
     *
     * @return array
     */
    public function getUaHttpHeaders()
    {
        return $this->uaHttpHeaders->getAll();
    }

    /**
     * Set the user agent.
     *
     * @param  string|null  $userAgent
     */
    public function setUserAgent($userAgent = null)
    {
        if (is_null($userAgent)) {
            $userAgent = '';

            foreach ($this->getUaHttpHeaders() as $altHeader) {
                if (isset($this->httpHeaders[$altHeader])) {
                    $userAgent .= $this->httpHeaders[$altHeader].' ';
                }
            }

            // If no headers were found, keep it as null.
            if ($userAgent === '') {
                $userAgent = null;
            }
        }

        return $this->userAgent = $userAgent;
    }

    /**
     * Check user agent string against the regex.
     *
     * @param  string|null  $userAgent
     * @return bool
     */
    public function isCrawler($userAgent = null)
    {
        $this->matches = [];

        // Exclusions are replaced with a space rather than removed, and the
        // result is not trimmed, so stripping can't join the text on either
        // side into a new token ('boLinuxt' into 'bot') or move a token to
        // the start or end of the string where an anchored pattern would
        // match it ('Mozilla/5.0 git/2.0' into 'git/2.0'). A run of
        // exclusions and the whitespace around it becomes a single space,
        // which keeps the string as short as it was when they were removed.
        $agent = preg_replace(
            "/(?:\s*{$this->compiledExclusions})+\s*/i",
            ' ',
            trim($userAgent ?: $this->userAgent ?: '')
        );

        if ($agent === null || trim($agent) === '') {
            return false;
        }

        $result = preg_match("/{$this->compiledRegex}/i", $agent, $this->matches);

        if ($result === false) {
            $this->matches = [];

            return false;
        }

        return (bool) $result;
    }

    /**
     * Return the matches.
     *
     * @return string|null
     */
    public function getMatches()
    {
        return isset($this->matches[0]) ? $this->matches[0] : null;
    }

    /**
     * @return string|null
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }
}
