<?php

class UserAgentTest extends PHPUnit_Framework_TestCase
{
    protected $CrawlerDetect;

    public function setUp()
    {
        $this->CrawlerDetect = new Jaybizzle\CrawlerDetect\CrawlerDetect();
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
}
