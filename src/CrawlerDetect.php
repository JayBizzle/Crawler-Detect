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
     * @var string
     */
    protected $userAgent = null;

    /**
     * Headers that contain a user agent.
     *
     * @var string[]
     */
    protected $httpHeaders = array();

    /**
     * Store regex matches.
     *
     * @var array
     */
    protected $matches = array();

    /**
     * Crawlers object.
     *
     * @var Crawlers
     */
    protected $crawlers;

    /**
     * Exclusions object.
     *
     * @var Exclusions
     */
    protected $exclusions;

    /**
     * Headers object.
     *
     * @var Headers
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
     * @param string[] $headers   Map of HTTP header values
     * @param string   $userAgent Browser user agent to detect
     */
    public function __construct(array $headers = null, $userAgent = null)
    {
        $this->setCrawlers(new Crawlers());
        $this->setExclusions(new Exclusions());
        $this->setUaHttpHeaders(new Headers());
        $this->setHttpHeaders($headers);
        $this->setUserAgent($userAgent);
    }

    /**
     * Compile the regex patterns into one regex string.
     *
     * @param array
     *
     * @return string
     */
    public function compileRegex($patterns)
    {
        return '(' . implode('|', $patterns) . ')';
    }

    /**
     * Get HTTP Headers
     *
     * @return array
     */
    public function getHttpHeaders()
    {
        return $this->httpHeaders;
    }

    /**
     * Set HTTP headers.
     *
     * @param array|null $httpHeaders
     * @return $this
     */
    public function setHttpHeaders($httpHeaders)
    {
        // Use global _SERVER if $httpHeaders aren't defined.
        if (empty($httpHeaders)) {
            $httpHeaders = $_SERVER;
        }

        // Clear existing headers.
        $this->httpHeaders = array();

        // Only save HTTP headers. In PHP land, that means
        // only _SERVER vars that start with HTTP_.
        foreach ($httpHeaders as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $this->httpHeaders[$key] = $value;
            }
        }

        return $this;
    }

    /**
     * Return user agent headers.
     *
     * @return string[]
     */
    public function getUaHttpHeaders()
    {
        return $this->uaHttpHeaders->getAll();
    }

    /**
     * Assign headers used to detect user agent
     *
     * @param Headers $headers
     * @return $this
     */
    public function setUaHttpHeaders(Headers $headers)
    {
        $this->uaHttpHeaders = $headers;
        return $this;
    }

    /**
     * Get, or detect, user agent string
     *
     * @return string
     */
    public function getUserAgent()
    {
        if ($this->userAgent) {
            return $this->userAgent;
        }

        // Detect from headers
        $userAgent = '';
        $headers = $this->getHttpHeaders();
        foreach ($this->getUaHttpHeaders() as $altHeader) {
            if (isset($headers[$altHeader])) {
                $userAgent .= $headers[$altHeader] . ' ';
            }
        }
        return $userAgent;
    }

    /**
     * Set the user agent.
     *
     * @param string $userAgent
     * @return $this
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    /**
     * Get crawlers list
     *
     * @return string[]
     */
    public function getCrawlers()
    {
        return $this->crawlers->getAll();
    }

    /**
     * @param Crawlers $crawlers
     * @return $this
     */
    public function setCrawlers(Crawlers $crawlers)
    {
        $this->crawlers = $crawlers;
        $this->compiledRegex = $this->compileRegex($this->getCrawlers());
        return $this;
    }

    /**
     * Get exclusions list
     *
     * @return string[]
     */
    public function getExclusions()
    {
        return $this->exclusions->getAll();
    }

    /**
     * @param Exclusions $exclusions
     * @return $this
     */
    public function setExclusions(Exclusions $exclusions)
    {
        $this->exclusions = $exclusions;
        $this->compiledExclusions = $this->compileRegex($this->getExclusions());
        return $this;
    }

    /**
     * Check user agent string against the regex.
     *
     * @param string|null $userAgent
     *
     * @return bool
     */
    public function isCrawler($userAgent = null)
    {
        $agent = trim(preg_replace(
            "/{$this->compiledExclusions}/i",
            '',
            $userAgent ?: $this->getUserAgent()
        ));

        if ($agent == '') {
            return false;
        }

        $result = preg_match("/{$this->compiledRegex}/i", $agent, $matches);

        if ($matches) {
            $this->matches = $matches;
        }

        return (bool)$result;
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
}
