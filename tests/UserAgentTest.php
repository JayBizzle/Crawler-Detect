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
use Jaybizzle\CrawlerDetect\Fixtures\Exclusions;
use PHPUnit\Framework\TestCase;

final class UserAgentTest extends TestCase
{
    protected $crawlerDetect;

    protected function setUp(): void
    {
        $this->crawlerDetect = new CrawlerDetect;
    }

    public function test_user_agents_are_bots()
    {
        $lines = file(__DIR__.'/data/user_agent/crawlers.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $test = $this->crawlerDetect->isCrawler($line);
            $this->assertTrue($test, $line);
        }
    }

    public function test_user_agents_are_devices()
    {
        $lines = file(__DIR__.'/data/user_agent/devices.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $test = $this->crawlerDetect->isCrawler($line);
            $this->assertFalse($test, $line);
        }
    }

    public function test_sec_ch_ua_are_bots()
    {
        $lines = file(__DIR__.'/data/sec_ch_ua/crawlers.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $test = $this->crawlerDetect->isCrawler($line);
            $this->assertTrue($test, $line);
        }
    }

    public function test_sec_ch_ua_are_devices()
    {
        $lines = file(__DIR__.'/data/sec_ch_ua/devices.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $test = $this->crawlerDetect->isCrawler($line);
            $this->assertFalse($test, $line);
        }
    }

    public function test_it_returns_correct_matched_bot_name()
    {
        $this->crawlerDetect->isCrawler('Mozilla/5.0 (iPhone; CPU iPhone OS 7_1 like Mac OS X) AppleWebKit (KHTML, like Gecko) Mobile (compatible; Yahoo Ad monitoring; https://help.yahoo.com/kb/yahoo-ad-monitoring-SLN24857.html)');

        $matches = $this->crawlerDetect->getMatches();

        $this->assertEquals($this->crawlerDetect->getMatches(), 'monitoring', $matches);
    }

    public function test_it_returns_user_agent()
    {
        $ua = 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_1 like Mac OS X) AppleWebKit (KHTML, like Gecko) Mobile (compatible; Yahoo Ad monitoring; https://help.yahoo.com/kb/yahoo-ad-monitoring-SLN24857.html)';
        $cd = new CrawlerDetect(null, $ua);

        $this->assertEquals($cd->getUserAgent(), $ua);
    }

    public function test_it_returns_full_matched_bot_name()
    {
        $this->crawlerDetect->isCrawler('somenaughtybot');

        $matches = $this->crawlerDetect->getMatches();

        $this->assertEquals($this->crawlerDetect->getMatches(), 'somenaughtybot', $matches);
    }

    public function test_it_returns_null_when_no_bot_detected()
    {
        $this->crawlerDetect->isCrawler('nothing to see here');

        $this->assertNull($this->crawlerDetect->getMatches());
    }

    public function test_empty_user_agent()
    {
        $test = $this->crawlerDetect->isCrawler('      ');

        $this->assertFalse($test);
    }

    public function test_current_visitor()
    {
        $headers = (array) json_decode('{"DOCUMENT_ROOT":"\/home\/test\/public_html","GATEWAY_INTERFACE":"CGI\/1.1","HTTP_ACCEPT":"*\/*","HTTP_ACCEPT_ENCODING":"gzip, deflate","HTTP_CACHE_CONTROL":"no-cache","HTTP_CONNECTION":"Keep-Alive","HTTP_FROM":"bingbot(at)microsoft.com","HTTP_HOST":"www.test.com","HTTP_PRAGMA":"no-cache","HTTP_USER_AGENT":"Mozilla\/5.0 (compatible; bingbot\/2.0; +http:\/\/www.bing.com\/bingbot.htm)","PATH":"\/bin:\/usr\/bin","QUERY_STRING":"order=closingDate","REDIRECT_STATUS":"200","REMOTE_ADDR":"127.0.0.1","REMOTE_PORT":"3360","REQUEST_METHOD":"GET","REQUEST_URI":"\/?test=testing","SCRIPT_FILENAME":"\/home\/test\/public_html\/index.php","SCRIPT_NAME":"\/index.php","SERVER_ADDR":"127.0.0.1","SERVER_ADMIN":"webmaster@test.com","SERVER_NAME":"www.test.com","SERVER_PORT":"80","SERVER_PROTOCOL":"HTTP\/1.1","SERVER_SIGNATURE":"","SERVER_SOFTWARE":"Apache","UNIQUE_ID":"Vx6MENRxerBUSDEQgFLAAAAAS","PHP_SELF":"\/index.php","REQUEST_TIME_FLOAT":1461619728.0705,"REQUEST_TIME":1461619728}');

        $cd = new CrawlerDetect($headers);

        $this->assertTrue($cd->isCrawler());
    }

    public function test_user_agent_passed_via_constructor()
    {
        $cd = new CrawlerDetect(null, 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_1 like Mac OS X) AppleWebKit (KHTML, like Gecko) Mobile (compatible; Yahoo Ad monitoring; https://help.yahoo.com/kb/yahoo-ad-monitoring-SLN24857.html)');

        $this->assertTrue($cd->isCrawler());
    }

    public function test_http_from_header()
    {
        $headers = (array) json_decode('{"DOCUMENT_ROOT":"\/home\/test\/public_html","GATEWAY_INTERFACE":"CGI\/1.1","HTTP_ACCEPT":"*\/*","HTTP_ACCEPT_ENCODING":"gzip, deflate","HTTP_CACHE_CONTROL":"no-cache","HTTP_CONNECTION":"Keep-Alive","HTTP_FROM":"googlebot(at)googlebot.com","HTTP_HOST":"www.test.com","HTTP_PRAGMA":"no-cache","HTTP_USER_AGENT":"Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/28.0.1500.71 Safari\/537.36","PATH":"\/bin:\/usr\/bin","QUERY_STRING":"order=closingDate","REDIRECT_STATUS":"200","REMOTE_ADDR":"127.0.0.1","REMOTE_PORT":"3360","REQUEST_METHOD":"GET","REQUEST_URI":"\/?test=testing","SCRIPT_FILENAME":"\/home\/test\/public_html\/index.php","SCRIPT_NAME":"\/index.php","SERVER_ADDR":"127.0.0.1","SERVER_ADMIN":"webmaster@test.com","SERVER_NAME":"www.test.com","SERVER_PORT":"80","SERVER_PROTOCOL":"HTTP\/1.1","SERVER_SIGNATURE":"","SERVER_SOFTWARE":"Apache","UNIQUE_ID":"Vx6MENRxerBUSDEQgFLAAAAAS","PHP_SELF":"\/index.php","REQUEST_TIME_FLOAT":1461619728.0705,"REQUEST_TIME":1461619728}');

        $cd = new CrawlerDetect($headers);

        $this->assertTrue($cd->isCrawler());
    }

    public function test_psr7_style_header_names()
    {
        // ServerRequestInterface::getHeaders() returns real header names with
        // an array of strings for each value.
        $cd = new CrawlerDetect([
            'Host' => ['www.test.com'],
            'User-Agent' => ['Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)'],
        ]);

        $this->assertTrue($cd->isCrawler());
    }

    public function test_lowercase_dashed_header_names()
    {
        // HttpFoundation's HeaderBag::all() lowercases the names it returns.
        $cd = new CrawlerDetect([
            'user-agent' => ['Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)'],
        ]);

        $this->assertTrue($cd->isCrawler());
    }

    public function test_scalar_header_values_are_accepted()
    {
        // Swoole and most Lambda event shapes give a plain string per header.
        $cd = new CrawlerDetect([
            'user-agent' => 'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)',
        ]);

        $this->assertTrue($cd->isCrawler());
    }

    public function test_from_header_is_honoured_from_a_non_sapi_source()
    {
        // Googlebot sometimes sends a genuine browser UA and identifies itself
        // in the From header instead. The UA alone is not a match.
        $headers = [
            'From' => ['googlebot(at)googlebot.com'],
            'User-Agent' => ['Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.71 Safari/537.36'],
        ];

        $cd = new CrawlerDetect($headers);

        $this->assertTrue($cd->isCrawler());
        $this->assertFalse($cd->isCrawler($headers['User-Agent'][0]));
    }

    public function test_sec_ch_ua_header_is_honoured_from_a_non_sapi_source()
    {
        $cd = new CrawlerDetect([
            'Sec-CH-UA' => ['"HeadlessChrome";v="129", "Not=A?Brand";v="8", "Chromium";v="129"'],
        ]);

        $this->assertTrue($cd->isCrawler());
    }

    public function test_mixed_case_sapi_header_names()
    {
        $cd = new CrawlerDetect([
            'Http_User_Agent' => 'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)',
        ]);

        $this->assertTrue($cd->isCrawler());
    }

    public function test_non_header_server_vars_are_ignored()
    {
        // A URL can legitimately carry a crawler name, so these _SERVER vars
        // must never be scanned even though their values would match.
        $cd = new CrawlerDetect([
            'REQUEST_URI' => '/?utm_source=bingbot',
            'QUERY_STRING' => 'utm_source=bingbot',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.71 Safari/537.36',
        ]);

        $this->assertFalse($cd->isCrawler());
    }

    public function test_http_prefixed_real_header_names_are_not_treated_as_sapi_keys()
    {
        // 'Http-User-Agent' is a custom header, not the User-Agent header, so
        // its value must never be read as one. Only an underscore-separated
        // SAPI key carries the prefix we strip.
        $cd = new CrawlerDetect([
            'Http-User-Agent' => ['Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)'],
            'Http-From' => ['googlebot(at)googlebot.com'],
            'User-Agent' => ['Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.71 Safari/537.36'],
        ]);

        $this->assertFalse($cd->isCrawler());
    }

    public function test_ua_http_headers_retain_their_sapi_prefix()
    {
        // Public API - callers rely on these names, so they must not change.
        $this->assertContains('HTTP_USER_AGENT', $this->crawlerDetect->getUaHttpHeaders());
        $this->assertContains('HTTP_FROM', $this->crawlerDetect->getUaHttpHeaders());
    }

    public function test_matches_does_not_persist_across_multiple_calls()
    {
        $this->crawlerDetect->isCrawler('Mozilla/5.0 (iPhone; CPU iPhone OS 7_1 like Mac OS X) AppleWebKit (KHTML, like Gecko) Mobile (compatible; Yahoo Ad monitoring; https://help.yahoo.com/kb/yahoo-ad-monitoring-SLN24857.html)');
        $matches = $this->crawlerDetect->getMatches();
        $this->assertEquals($this->crawlerDetect->getMatches(), 'monitoring', $matches);

        $this->crawlerDetect->isCrawler('This should not match');
        $matches = $this->crawlerDetect->getMatches();
        $this->assertNull($this->crawlerDetect->getMatches());

        // Empty
        $this->crawlerDetect->isCrawler('Mozilla/5.0 (iPhone; CPU iPhone OS 7_1 like Mac OS X) AppleWebKit (KHTML, like Gecko) Mobile (compatible; Yahoo Ad monitoring; https://help.yahoo.com/kb/yahoo-ad-monitoring-SLN24857.html)');
        $this->crawlerDetect->isCrawler('');
        $this->assertNull($this->crawlerDetect->getMatches());

        // Excluded
        $this->crawlerDetect->isCrawler('Mozilla/5.0 (iPhone; CPU iPhone OS 7_1 like Mac OS X) AppleWebKit (KHTML, like Gecko) Mobile (compatible; Yahoo Ad monitoring; https://help.yahoo.com/kb/yahoo-ad-monitoring-SLN24857.html)');
        $this->crawlerDetect->isCrawler('iPod');
        $this->assertNull($this->crawlerDetect->getMatches());
    }

    public function test_the_regex_patterns_are_unique()
    {
        $crawlers = new Crawlers;

        $this->assertEquals(count($crawlers->getAll()), count(array_unique($crawlers->getAll())));
    }

    public function test_the_fixture_user_agents_are_unique()
    {
        foreach (glob(__DIR__.'/data/*/*.txt') as $file) {
            $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $duplicates = array_keys(array_filter(array_count_values($lines), function ($count) {
                return $count > 1;
            }));

            $this->assertSame([], $duplicates, 'Duplicate lines in '.basename(dirname($file)).'/'.basename($file));
        }
    }

    public function test_there_are_no_regex_collisions()
    {
        $crawlers = new Crawlers;
        $all = $crawlers->getAll();

        // Each pattern must not match the literal text of any other pattern.
        // One preg_grep per pattern covers every ordered pair in a single
        // pass, instead of two preg_match calls per pair (~4M calls).
        $literals = array_map('stripslashes', $all);

        $collisions = [];

        foreach ($all as $key => $regex) {
            $matches = preg_grep('/'.$regex.'/i', $literals);

            // A pattern may match its own literal text.
            unset($matches[$key]);

            if ($matches !== []) {
                $collisions[] = $regex.' collided with: '.implode(', ', $matches);
            }
        }

        $this->assertSame([], $collisions, "Patterns collide:\n".implode("\n", $collisions));
    }

    public function test_is_crawler_with_explicit_agent_does_not_change_stored_agent()
    {
        $ua = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36';
        $cd = new CrawlerDetect(null, $ua);

        $cd->isCrawler('Googlebot/2.1');

        $this->assertEquals($ua, $cd->getUserAgent());
    }

    /**
     * With a backtrack limit of 1, either the exclusions preg_replace or the
     * crawler preg_match can be the call that fails, depending on PCRE's
     * internal state, so this doesn't pin down which branch it covers.
     */
    public function test_is_crawler_returns_false_when_pcre_errors()
    {
        $originalLimit = ini_get('pcre.backtrack_limit');
        ini_set('pcre.backtrack_limit', '1');

        try {
            $result = @$this->crawlerDetect->isCrawler('Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');

            $this->assertFalse($result);
            $this->assertNull($this->crawlerDetect->getMatches());
        } finally {
            ini_set('pcre.backtrack_limit', $originalLimit);
        }
    }

    public function test_is_crawler_returns_false_when_crawler_regex_errors()
    {
        $property = new \ReflectionProperty($this->crawlerDetect, 'compiledRegex');
        if (PHP_VERSION_ID < 80100) {
            $property->setAccessible(true);
        }
        $property->setValue($this->crawlerDetect, '(unclosed');

        $result = @$this->crawlerDetect->isCrawler('Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');

        $this->assertFalse($result);
        $this->assertNull($this->crawlerDetect->getMatches());
    }

    /**
     * The catch-all pattern starts with an open-ended character class. Left
     * unanchored it is retried from every character of a long token, which is
     * quadratic and, without the PCRE JIT, took over four seconds for the
     * 8,000 characters that fit in a single request header.
     */
    public function test_long_tokens_are_matched_in_linear_time_without_jit()
    {
        $originalJit = ini_get('pcre.jit');
        ini_set('pcre.jit', '0');

        try {
            $start = microtime(true);
            $result = $this->crawlerDetect->isCrawler(str_repeat('a', 8000));
            $elapsed = microtime(true) - $start;
        } finally {
            ini_set('pcre.jit', $originalJit);
        }

        $this->assertFalse($result);
        $this->assertLessThan(1.0, $elapsed, 'isCrawler() took '.round($elapsed, 2).'s on an 8,000 character token.');
    }

    public function test_catch_all_still_returns_the_whole_token()
    {
        $this->assertTrue($this->crawlerDetect->isCrawler('Mozilla/5.0 (compatible; some-naughty_bot2/1.0)'));
        $this->assertEquals('some-naughty_bot', $this->crawlerDetect->getMatches());

        $this->assertTrue($this->crawlerDetect->isCrawler(str_repeat('a', 8000).'bot'));
        $this->assertEquals(str_repeat('a', 8000).'bot', $this->crawlerDetect->getMatches());
    }

    /**
     * The exclusions strip browser tokens before the crawler regex runs, so a
     * sloppy exclusion can eat part of a signature: a wildcard '.' after
     * 'Firefox' once reduced a hypothetical 'FirefoxBot' to 'ot', and ' Intel'
     * reduced the real 'Name Intelligence' signature to 'Nameligence'. Every
     * plain-literal signature must still match after stripping when it sits
     * inside an ordinary browser user agent.
     */
    public function test_exclusions_do_not_damage_signatures()
    {
        $shell = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36 %s (+http://example.com/info)';
        $exclusions = '/'.$this->crawlerDetect->compileRegex((new Exclusions)->getAll()).'/i';

        $damaged = [];

        foreach ((new Crawlers)->getAll() as $pattern) {
            // Only a plain literal can be embedded verbatim.
            if (preg_match('/[\^\$\[\]\(\)\|\?\*\+\{\}]/', $pattern)) {
                continue;
            }

            $stripped = trim(preg_replace($exclusions, '', sprintf($shell, stripslashes($pattern))));

            if (! preg_match('/'.$pattern.'/i', $stripped)) {
                $damaged[] = $pattern.' (became: '.$stripped.')';
            }
        }

        $this->assertSame([], $damaged, "Exclusions damage these signatures:\n".implode("\n", $damaged));
    }

    public function test_all_regex_patterns_are_valid()
    {
        $crawlers = new Crawlers;

        foreach ($crawlers->getAll() as $pattern) {
            $result = @preg_match('/'.$pattern.'/i', '');
            $this->assertNotFalse($result, 'Invalid regex pattern: '.$pattern);
        }
    }

    /**
     * Regression guard for issue #594.
     *
     * Amazon CloudFront has been added and removed from the crawler list
     * three times (#392 added, #410 removed, #504 re-added, #602 removed).
     * It is a reverse proxy: for sites hosted behind it, the 'Amazon CloudFront'
     * UA on inbound requests is the CDN fetching from origin on behalf of real
     * end users, not crawler activity. Treating it as a crawler silently breaks
     * analytics, auth, and rate-limiting for AWS-hosted sites.
     *
     * If this test fails, do NOT delete it to make a re-add green. Open a fresh
     * design discussion on the issue tracker first.
     */
    public function test_amazon_cloudfront_must_not_be_classified_as_a_crawler()
    {
        $this->assertFalse(
            $this->crawlerDetect->isCrawler('Amazon CloudFront'),
            'Amazon CloudFront UA was matched by a crawler signature. '.
            'See issue #594 — this UA represents a reverse-proxy origin fetch on '.
            'behalf of real users, not crawler traffic. Do not re-add it.'
        );
    }
}
