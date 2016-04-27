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

    public function testCurrentVisitor()
    {
        $headers = (array) json_decode('{"DOCUMENT_ROOT":"\/home\/test\/public_html","GATEWAY_INTERFACE":"CGI\/1.1","HTTP_ACCEPT":"*\/*","HTTP_ACCEPT_ENCODING":"gzip, deflate","HTTP_CACHE_CONTROL":"no-cache","HTTP_CONNECTION":"Keep-Alive","HTTP_FROM":"bingbot(at)microsoft.com","HTTP_HOST":"www.test.com","HTTP_PRAGMA":"no-cache","HTTP_USER_AGENT":"Mozilla\/5.0 (compatible; bingbot\/2.0; +http:\/\/www.bing.com\/bingbot.htm)","PATH":"\/bin:\/usr\/bin","QUERY_STRING":"order=closingDate","REDIRECT_STATUS":"200","REMOTE_ADDR":"127.0.0.1","REMOTE_PORT":"3360","REQUEST_METHOD":"GET","REQUEST_URI":"\/?test=testing","SCRIPT_FILENAME":"\/home\/test\/public_html\/index.php","SCRIPT_NAME":"\/index.php","SERVER_ADDR":"127.0.0.1","SERVER_ADMIN":"webmaster@test.com","SERVER_NAME":"www.test.com","SERVER_PORT":"80","SERVER_PROTOCOL":"HTTP\/1.1","SERVER_SIGNATURE":"","SERVER_SOFTWARE":"Apache","UNIQUE_ID":"Vx6MENRxerBUSDEQgFLAAAAAS","PHP_SELF":"\/index.php","REQUEST_TIME_FLOAT":1461619728.0705,"REQUEST_TIME":1461619728}');

        $cd = new CrawlerDetect($headers);

        $this->assertEquals($cd->isCrawler(), true);
    }

    public function testUserAgentPassedViaConstructor()
    {
        $cd = new CrawlerDetect(null, 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_1 like Mac OS X) AppleWebKit (KHTML, like Gecko) Mobile (compatible; Yahoo Ad monitoring; https://help.yahoo.com/kb/yahoo-ad-monitoring-SLN24857.html)');

        $this->assertEquals($cd->isCrawler(), true);
    }

    public function testHttpFromHeader()
    {
        $headers = (array) json_decode('{"DOCUMENT_ROOT":"\/home\/test\/public_html","GATEWAY_INTERFACE":"CGI\/1.1","HTTP_ACCEPT":"*\/*","HTTP_ACCEPT_ENCODING":"gzip, deflate","HTTP_CACHE_CONTROL":"no-cache","HTTP_CONNECTION":"Keep-Alive","HTTP_FROM":"googlebot(at)googlebot.com","HTTP_HOST":"www.test.com","HTTP_PRAGMA":"no-cache","HTTP_USER_AGENT":"Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/28.0.1500.71 Safari\/537.36","PATH":"\/bin:\/usr\/bin","QUERY_STRING":"order=closingDate","REDIRECT_STATUS":"200","REMOTE_ADDR":"127.0.0.1","REMOTE_PORT":"3360","REQUEST_METHOD":"GET","REQUEST_URI":"\/?test=testing","SCRIPT_FILENAME":"\/home\/test\/public_html\/index.php","SCRIPT_NAME":"\/index.php","SERVER_ADDR":"127.0.0.1","SERVER_ADMIN":"webmaster@test.com","SERVER_NAME":"www.test.com","SERVER_PORT":"80","SERVER_PROTOCOL":"HTTP\/1.1","SERVER_SIGNATURE":"","SERVER_SOFTWARE":"Apache","UNIQUE_ID":"Vx6MENRxerBUSDEQgFLAAAAAS","PHP_SELF":"\/index.php","REQUEST_TIME_FLOAT":1461619728.0705,"REQUEST_TIME":1461619728}');

        $cd = new CrawlerDetect($headers);

        $this->assertEquals($cd->isCrawler(), true);
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
