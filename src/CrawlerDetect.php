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

use Jaybizzle\CrawlerDetect\Detectors\AgentDetector;
use Jaybizzle\CrawlerDetect\Detectors\IpDetector;

class CrawlerDetect
{
    /**
     * Check agent setting.
     *
     * @var null
     */
    protected $checkAgent = null;

    /**
     * Check IP setting.
     * 
     * @var null
     */
    protected $checkIp = null;

    /**
     * Array of detectors.
     * 
     * @var array
     */
    protected $detectors = [];

    /**
     * Check the user agent.
     * 
     * @return $this
     */
    public function agent($userAgent = null)
    {
        $this->checkAgent = true;

        if(!isset($detectors['agent'])) {
            $this->detectors['agent'] = new AgentDetector($userAgent);
        }

        return $this;
    }

    /**
     * Check the IP address.
     * 
     * @return $this
     */
    public function ip($userIp)
    {
        $this->checkIp = true;

        if(!isset($detectors['ip'])) {
            $this->detectors['ip'] = new IpDetector($userIp);
        }

        return $this;
    }

    /**
     * Check user agent string against the regex.
     *
     * @param string|null $userAgent
     *
     * @return bool
     */
    public function isCrawler()
    {
        if ($this->checkAgent && ! $this->checkIp) {
            return $this->detectors['agent']->check();
        }

        if ($this->checkIp && ! $this->checkAgent) {
            return $this->detectors['ip']->check();
        }

        if (is_null($this->checkIp) && is_null($this->checkAgent)) {
            $this->ip()->agent();
            return $this->detectors['agent']->check() && $this->detectors['ip']->check();
        }
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
