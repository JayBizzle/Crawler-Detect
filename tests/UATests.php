<?php

/*
 * This file is part of Crawler Detect - the web crawler detection library.
 *
 * (c) Mark Beech <m@rkbee.ch>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Jaybizzle\CrawlerDetect\Fixtures\Crawlers;

class UserAgentTest extends PHPUnit_Framework_TestCase
{
    protected $CrawlerDetect;

    public function setUp()
    {
        $this->CrawlerDetect = new CrawlerDetect();
    }

    public function testBots()
    {
        $lines = file(__DIR__.'/crawlers.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $test = $this->CrawlerDetect->isCrawler($line);
            $this->assertEquals($test, true, $line);
        }
    }

    public function testDevices()
    {
        $lines = file(__DIR__.'/devices.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $test = $this->CrawlerDetect->isCrawler($line);
            $this->assertEquals($test, false, $line);
        }
    }

    public function testReturnsCorrectMatchedBotName()
    {
        $test = $this->CrawlerDetect->isCrawler('Mozilla/5.0 (iPhone; CPU iPhone OS 7_1 like Mac OS X) AppleWebKit (KHTML, like Gecko) Mobile (compatible; Yahoo Ad monitoring; https://help.yahoo.com/kb/yahoo-ad-monitoring-SLN24857.html)');

        $matches = $this->CrawlerDetect->getMatches();

        $this->assertEquals($this->CrawlerDetect->getMatches(), 'Yahoo Ad monitoring', $matches);
    }

    public function testEmptyUserAgent()
    {
        $test = $this->CrawlerDetect->isCrawler('      ');

        $this->assertEquals($test, false);       
    }

    public function testForRegexCollision()
    {
        $crawlers = new Crawlers();

        foreach ($crawlers->getAll() as $key1 => $regex) {
            foreach ($crawlers->getAll() as $key2 => $compare) {
                // Dont check this regex against itself
                if ($key1 != $key2) {
                    preg_match('/'.$regex.'/i', stripslashes($compare), $matches);

                    $this->assertEmpty($matches, $regex.' collided with '.$compare);
                }
            }
        }
    }
}
