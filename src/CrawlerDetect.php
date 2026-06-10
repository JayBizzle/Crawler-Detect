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
        return '(?:'.implode('|', $patterns).')';
    }

    /**
     * Set HTTP headers.
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

        // Only save HTTP headers. In PHP land, that means
        // only _SERVER vars that start with HTTP_.
        foreach ($httpHeaders as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $this->httpHeaders[$key] = $value;
            }
        }
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

        $agent = preg_replace(
            "/{$this->compiledExclusions}/i",
            '',
            $userAgent ?: $this->userAgent ?: ''
        );

        if ($agent === null || trim($agent) === '') {
            return false;
        }

        $agent = trim($agent);

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
