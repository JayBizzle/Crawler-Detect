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

    /** @test */
    public function user_agents_are_bots()
    {
        $lines = file(__DIR__.'/crawlers.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $test = $this->CrawlerDetect->agent($line)->isCrawler();
            $this->assertEquals($test, true, $line);
        }
    }

    /** @test */
    public function user_ips_are_bots()
    {
        $test = $this->CrawlerDetect->ip('80.140.2.2')->isCrawler();
        $this->assertEquals($test, true);
    }
}
