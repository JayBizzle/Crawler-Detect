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
            $test = $this->CrawlerDetect->isCrawler($line);
            $this->assertEquals($test, true, $line);
        }
    }

    /** @test */
    public function user_agents_are_devices()
    {
        $lines = file(__DIR__.'/devices.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $test = $this->CrawlerDetect->isCrawler($line);
            $this->assertEquals($test, false, $line);
        }
    }

    /** @test */
    public function it_returns_correct_matched_bot_name()
    {
        $test = $this->CrawlerDetect->isCrawler('Mozilla/5.0 (iPhone; CPU iPhone OS 7_1 like Mac OS X) AppleWebKit (KHTML, like Gecko) Mobile (compatible; Yahoo Ad monitoring; https://help.yahoo.com/kb/yahoo-ad-monitoring-SLN24857.html)');

        $matches = $this->CrawlerDetect->getMatches();

        $this->assertEquals($this->CrawlerDetect->getMatches(), 'Yahoo Ad monitoring', $matches);
    }

    /** @test */
    public function it_returns_null_when_no_bot_detected()
    {
        $test = $this->CrawlerDetect->isCrawler('nothing to see here');

        $matches = $this->CrawlerDetect->getMatches();

        $this->assertEquals($this->CrawlerDetect->getMatches(), null, $matches);
    }

    /** @test */
    public function empty_user_agent()
    {
        $test = $this->CrawlerDetect->isCrawler('      ');

        $this->assertEquals($test, false);
    }

    /** @test */
    public function current_visitor()
    {
        $headers = (array) json_decode('{"DOCUMENT_ROOT":"\/home\/test\/public_html","GATEWAY_INTERFACE":"CGI\/1.1","HTTP_ACCEPT":"*\/*","HTTP_ACCEPT_ENCODING":"gzip, deflate","HTTP_CACHE_CONTROL":"no-cache","HTTP_CONNECTION":"Keep-Alive","HTTP_FROM":"bingbot(at)microsoft.com","HTTP_HOST":"www.test.com","HTTP_PRAGMA":"no-cache","HTTP_USER_AGENT":"Mozilla\/5.0 (compatible; bingbot\/2.0; +http:\/\/www.bing.com\/bingbot.htm)","PATH":"\/bin:\/usr\/bin","QUERY_STRING":"order=closingDate","REDIRECT_STATUS":"200","REMOTE_ADDR":"127.0.0.1","REMOTE_PORT":"3360","REQUEST_METHOD":"GET","REQUEST_URI":"\/?test=testing","SCRIPT_FILENAME":"\/home\/test\/public_html\/index.php","SCRIPT_NAME":"\/index.php","SERVER_ADDR":"127.0.0.1","SERVER_ADMIN":"webmaster@test.com","SERVER_NAME":"www.test.com","SERVER_PORT":"80","SERVER_PROTOCOL":"HTTP\/1.1","SERVER_SIGNATURE":"","SERVER_SOFTWARE":"Apache","UNIQUE_ID":"Vx6MENRxerBUSDEQgFLAAAAAS","PHP_SELF":"\/index.php","REQUEST_TIME_FLOAT":1461619728.0705,"REQUEST_TIME":1461619728}');

        $cd = new CrawlerDetect($headers);

        $this->assertEquals($cd->isCrawler(), true);
    }

    /** @test */
    public function user_agent_passed_via_contructor()
    {
        $cd = new CrawlerDetect(null, 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_1 like Mac OS X) AppleWebKit (KHTML, like Gecko) Mobile (compatible; Yahoo Ad monitoring; https://help.yahoo.com/kb/yahoo-ad-monitoring-SLN24857.html)');

        $this->assertEquals($cd->isCrawler(), true);
    }

    /** @test */
    public function http_from_header()
    {
        $headers = (array) json_decode('{"DOCUMENT_ROOT":"\/home\/test\/public_html","GATEWAY_INTERFACE":"CGI\/1.1","HTTP_ACCEPT":"*\/*","HTTP_ACCEPT_ENCODING":"gzip, deflate","HTTP_CACHE_CONTROL":"no-cache","HTTP_CONNECTION":"Keep-Alive","HTTP_FROM":"googlebot(at)googlebot.com","HTTP_HOST":"www.test.com","HTTP_PRAGMA":"no-cache","HTTP_USER_AGENT":"Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/28.0.1500.71 Safari\/537.36","PATH":"\/bin:\/usr\/bin","QUERY_STRING":"order=closingDate","REDIRECT_STATUS":"200","REMOTE_ADDR":"127.0.0.1","REMOTE_PORT":"3360","REQUEST_METHOD":"GET","REQUEST_URI":"\/?test=testing","SCRIPT_FILENAME":"\/home\/test\/public_html\/index.php","SCRIPT_NAME":"\/index.php","SERVER_ADDR":"127.0.0.1","SERVER_ADMIN":"webmaster@test.com","SERVER_NAME":"www.test.com","SERVER_PORT":"80","SERVER_PROTOCOL":"HTTP\/1.1","SERVER_SIGNATURE":"","SERVER_SOFTWARE":"Apache","UNIQUE_ID":"Vx6MENRxerBUSDEQgFLAAAAAS","PHP_SELF":"\/index.php","REQUEST_TIME_FLOAT":1461619728.0705,"REQUEST_TIME":1461619728}');

        $cd = new CrawlerDetect($headers);

        $this->assertEquals($cd->isCrawler(), true);
    }

    /** @test */
    public function the_regex_patterns_are_unique()
    {
        $crawlers = new Crawlers();

        $this->assertEquals(count($crawlers->getAll()), count(array_unique($crawlers->getAll())));
    }

    /** @test */
    public function there_are_no_regex_collisions()
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
