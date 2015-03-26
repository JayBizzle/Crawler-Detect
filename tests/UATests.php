<?php

class UserAgentTest extends PHPUnit_Framework_TestCase
{
	protected $CrawlerDetect;

	public function setUp()
	{
		$this->CrawlerDetect = new Jaybizzle\CrawlerDetect\CrawlerDetect;
	}

	public function testBots()
	{
		$lines = file(__DIR__ . '/crawlers.txt');

		foreach($lines as $line) {
			$test = $this->CrawlerDetect->isCrawler($line);
			$this->assertEquals($test, true, $line);
		}
	}

	public function testDevices()
	{
		$lines = file(__DIR__ . '/devices.txt');

		foreach($lines as $line) {
			$test = $this->CrawlerDetect->isCrawler($line);
			$this->assertEquals($test, false, $line);
		}
	}
}
