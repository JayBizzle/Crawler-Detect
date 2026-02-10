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
    protected $userAgent = null;

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
     * Class constructor.
     *
     * @param array<string, string>|null $headers HTTP headers array
     * @param string|null $userAgent User agent string
     */
    public function __construct(?array $headers = null, ?string $userAgent = null)
    {
        $this->crawlers = new Crawlers;
        $this->exclusions = new Exclusions;
        $this->uaHttpHeaders = new Headers;

        $this->compiledRegex = $this->compileRegex($this->crawlers->getAll());
        $this->compiledExclusions = $this->compileRegex($this->exclusions->getAll());

        $this->setHttpHeaders($headers);
        $this->setUserAgent($userAgent);
    }

    /**
     * Compile the regex patterns into one regex string.
     *
     * @param array<int, string> $patterns Array of regex patterns
     * @return string Compiled regex pattern
     */
    public function compileRegex(array $patterns): string
    {
        return '('.implode('|', $patterns).')';
    }

    /**
     * Set HTTP headers.
     *
     * @param array<string, string>|null $httpHeaders HTTP headers array
     * @return void
     */
    public function setHttpHeaders(?array $httpHeaders): void
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
     * @return array<int, string> Array of user agent header keys
     */
    public function getUaHttpHeaders(): array
    {
        return $this->uaHttpHeaders->getAll();
    }

    /**
     * Set the user agent.
     *
     * @param string|null $userAgent User agent string
     * @return string|null The set user agent
     */
    public function setUserAgent(?string $userAgent): ?string
    {
        if (is_null($userAgent)) {
            $userAgent = '';
            foreach ($this->getUaHttpHeaders() as $altHeader) {
                if (isset($this->httpHeaders[$altHeader])) {
                    $userAgent .= $this->httpHeaders[$altHeader].' ';
                }
            }
            $userAgent = $userAgent !== '' ? $userAgent : null;
        }

        return $this->userAgent = $userAgent;
    }

    /**
     * Check user agent string against the regex.
     *
     * @param string|null $userAgent User agent string to check
     * @return bool True if crawler detected, false otherwise
     */
    public function isCrawler(?string $userAgent = null): bool
    {
        $agent = trim(preg_replace(
            "/{$this->compiledExclusions}/i",
            '',
            $userAgent ?: $this->userAgent ?: ''
        ));

        if ($agent === '') {
            $this->matches = [];

            return false;
        }

        return (bool) preg_match("/{$this->compiledRegex}/i", $agent, $this->matches);
    }

    /**
     * Return the matches.
     *
     * @return string|null The matched bot name or null if no match
     */
    public function getMatches(): ?string
    {
        return isset($this->matches[0]) ? $this->matches[0] : null;
    }

    /**
     * Get the user agent string.
     *
     * @return string|null The user agent string
     */
    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }
}
