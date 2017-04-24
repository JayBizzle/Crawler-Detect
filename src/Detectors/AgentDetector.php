<?php

/*
 * This file is part of Crawler Detect - the web crawler detection library.
 *
 * (c) Mark Beech <m@rkbee.ch>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jaybizzle\CrawlerDetect\Detectors;

use Jaybizzle\CrawlerDetect\Fixtures\Headers;
use Jaybizzle\CrawlerDetect\Fixtures\Crawlers;
use Jaybizzle\CrawlerDetect\Fixtures\Exclusions;

class AgentDetector
{
    /**
     * The user agent.
     *
     * @var null
     */
    protected $userAgent = null;

    /**
     * Headers that contain a user agent.
     *
     * @var array
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

    public function __construct($userAgent)
    {
        $this->userAgent = $userAgent;

        $this->crawlers = new Crawlers();
        $this->exclusions = new Exclusions();
        $this->uaHttpHeaders = new Headers();

        $this->compiledRegex = $this->compileRegex($this->crawlers->getAll());
        $this->compiledExclusions = $this->compileRegex($this->exclusions->getAll());
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
        return '('.implode('|', $patterns).')';
    }

    /**
     * Set HTTP headers.
     *
     * @param array|null $httpHeaders
     */
    public function setHttpHeaders()
    {
        $httpHeaders = $_SERVER;

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
     * @return void
     */
    public function setUserAgent()
    {
        foreach ($this->getUaHttpHeaders() as $altHeader) {
            if (false === empty($this->httpHeaders[$altHeader])) { // @todo: should use getHttpHeader(), but it would be slow.
                $this->userAgent .= $this->httpHeaders[$altHeader].' ';
            }
        }

        $this->userAgent = (! empty($this->userAgent) ? trim($this->userAgent) : null);
    }

    /**
     * Perform the check.
     * 
     * @param  string|null $userAgent
     * @return bool
     */
    public function check($userAgent = null)
    {
        if (is_null($this->userAgent) && ! is_null($userAgent)) {
            $this->userAgent = $userAgent;
        }

        if (is_null($this->userAgent)) {
            $this->setHttpHeaders();
            $this->setUserAgent();
        }

        $agent = preg_replace('/'.$this->compiledExclusions.'/i', '', $this->userAgent);

        if (strlen(trim($this->userAgent)) == 0) {
            return false;
        }

        $result = preg_match('/'.$this->compiledRegex.'/i', trim($this->userAgent), $matches);

        if ($matches) {
            $this->matches = $matches;
        }

        return (bool) $result;
    }
}
