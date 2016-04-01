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

    public function testReturnsCorrectMatchedBotName()
    {
        $test = $this->CrawlerDetect->isCrawler('Mozilla/5.0 (iPhone; CPU iPhone OS 7_1 like Mac OS X) AppleWebKit (KHTML, like Gecko) Mobile (compatible; Yahoo Ad monitoring; https://help.yahoo.com/kb/yahoo-ad-monitoring-SLN24857.html)');

        $matches = $this->CrawlerDetect->getMatches();

        $this->assertEquals($this->CrawlerDetect->getMatches(), 'Yahoo Ad monitoring', $matches);
    }

    public function testForRegexCollision()
    {
        $crawlers = $this->CrawlerDetect->getCrawlers();

        foreach ($crawlers as $regex) {
            foreach ($crawlers as $compare) {
                // Dont check this regex against itself
                if ($regex != $compare) {
                    preg_match('/'.$regex.'/i', stripslashes($compare), $matches);

                    $this->assertEmpty($matches, $regex.' collided with '.$compare);
                }
            }
        }
    }
}
