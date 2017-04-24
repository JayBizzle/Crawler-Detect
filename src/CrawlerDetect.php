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

use Jaybizzle\CrawlerDetect\Fixtures\Ips;
use Jaybizzle\CrawlerDetect\Fixtures\Headers;
use Jaybizzle\CrawlerDetect\Fixtures\Crawlers;
use Jaybizzle\CrawlerDetect\Fixtures\Exclusions;

class CrawlerDetect
{
    /**
     * The user agent.
     *
     * @var null
     */
    protected $userAgent = null;

    protected $checkAgent = null;

    protected $checkIp = null;

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

    /**
     * Class constructor.
     */
    public function __construct()
    {
        //
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
     * @param string|null $userAgent
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
     * Check the user agent.
     * 
     * @return $this
     */
    public function agent($userAgent = null)
    {
        $this->checkAgent = true;

        $this->userAgent = $userAgent;

        $this->crawlers = new Crawlers();
        $this->exclusions = new Exclusions();
        $this->uaHttpHeaders = new Headers();

        $this->compiledRegex = $this->compileRegex($this->crawlers->getAll());
        $this->compiledExclusions = $this->compileRegex($this->exclusions->getAll());

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

        $this->userIp = $userIp;

        return $this;
    }

    public function checkAgent()
    {
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

    public function checkIp()
    {
        $ips = new Ips();

        foreach ($ips->getAll() as $ip) {
            if ($this->ipInRange($this->userIp, $ip) === true) {
                return true;
            }
        }

        return false;
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
            return $this->checkAgent();
        }

        if ($this->checkIp && ! $this->checkAgent) {
            return $this->checkIp();
        }

        if (is_null($this->checkIp) && is_null($this->checkAgent)) {
            return $this->checkAgent() && $this->checkIp();
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

    public function ipInRange($ip, $range)
    {
        if (strpos($range, '/') !== false) {
            // $range is in IP/NETMASK format
        list($range, $netmask) = explode('/', $range, 2);

            if (strpos($netmask, '.') !== false) {
                // $netmask is a 255.255.0.0 format
          $netmask = str_replace('*', '0', $netmask);
                $netmask_dec = ip2long($netmask);

                return ((ip2long($ip) & $netmask_dec) == (ip2long($range) & $netmask_dec));
            } else {
                // $netmask is a CIDR size block
          // fix the range argument
          $x = explode('.', $range);
                while (count($x) < 4) {
                    $x[] = '0';
                }
                list($a, $b, $c, $d) = $x;
                $range = sprintf('%u.%u.%u.%u', empty($a) ? '0' : $a, empty($b) ? '0' : $b, empty($c) ? '0' : $c, empty($d) ? '0' : $d);
                $range_dec = ip2long($range);
                $ip_dec = ip2long($ip);

          # Strategy 1 - Create the netmask with 'netmask' 1s and then fill it to 32 with 0s
          #$netmask_dec = bindec(str_pad('', $netmask, '1') . str_pad('', 32-$netmask, '0'));

          # Strategy 2 - Use math to create it
          $wildcard_dec = pow(2, (32 - $netmask)) - 1;
                $netmask_dec = ~$wildcard_dec;

                return (($ip_dec & $netmask_dec) == ($range_dec & $netmask_dec));
            }
        } else {
            // range might be 255.255.*.* or 1.2.3.0-1.2.3.255
        if (strpos($range, '*') !== false) { // a.b.*.* format
          // Just convert to A-B format by setting * to 0 for A and 255 for B
          $lower = str_replace('*', '0', $range);
            $upper = str_replace('*', '255', $range);
            $range = "$lower-$upper";
        }

            if (strpos($range, '-') !== false) { // A-B format
          list($lower, $upper) = explode('-', $range, 2);
                $lower_dec = (float) sprintf('%u', ip2long($lower));
                $upper_dec = (float) sprintf('%u', ip2long($upper));
                $ip_dec = (float) sprintf('%u', ip2long($ip));

                return (($ip_dec >= $lower_dec) && ($ip_dec <= $upper_dec));
            }

            echo 'Range argument is not in 1.2.3.4/24 or 1.2.3.4/255.255.255.0 format';

            return false;
        }
    }

    // decbin32
    // In order to simplify working with IP addresses (in binary) and their
    // netmasks, it is easier to ensure that the binary strings are padded
    // with zeros out to 32 characters - IP addresses are 32 bit numbers
    public function decbin32($dec)
    {
        return str_pad(decbin($dec), 32, '0', STR_PAD_LEFT);
    }
}
