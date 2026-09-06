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
use Jaybizzle\CrawlerDetect\Fixtures\Categories;
use PHPUnit\Framework\TestCase;

final class CategoryTest extends TestCase
{
    protected $crawlerDetect;

    protected function setUp(): void
    {
        $this->crawlerDetect = new CrawlerDetect;
    }

    /**
     * The category names are part of the public API: callers switch on them,
     * so adding one is fine but renaming or removing one is a breaking change.
     */
    public function test_category_names_are_stable()
    {
        $this->assertSame([
            'ai-user',
            'ai-search',
            'ai-training',
            'search',
            'social',
            'feed',
            'seo',
            'monitoring',
            'security',
            'archiver',
            'scraper',
            'headless',
            'http-library',
        ], $this->crawlerDetect->getCategories());
    }

    public function test_category_fixtures_are_detected_and_classified()
    {
        foreach ($this->crawlerDetect->getCategories() as $category) {
            foreach ($this->fixtureLines($category) as $line) {
                $this->assertTrue($this->crawlerDetect->isCrawler($line), "Not detected as a crawler: $line");
                $this->assertSame($category, $this->crawlerDetect->getCategory(), "Wrong category for: $line");
            }
        }
    }

    public function test_every_category_has_a_fixture_file_and_vice_versa()
    {
        $categories = $this->crawlerDetect->getCategories();

        foreach ($categories as $category) {
            $this->assertFileExists($this->fixturePath($category));
        }

        foreach (glob(__DIR__.'/data/categories/*.txt') ?: [] as $file) {
            $this->assertContains(basename($file, '.txt'), $categories, "Fixture file $file has no matching category");
        }
    }

    /**
     * Every category pattern must be backed by at least one real user agent
     * in its own fixture file, so the list cannot fill up with guesses.
     */
    public function test_every_category_pattern_matches_a_fixture_line()
    {
        $dead = [];

        foreach ((new Categories)->getAll() as $category => $patterns) {
            $lines = $this->fixtureLines($category);

            foreach ($patterns as $pattern) {
                if (preg_grep('/'.$pattern.'/i', $lines) === []) {
                    $dead[] = "$category: $pattern";
                }
            }
        }

        $this->assertSame([], $dead, "Category patterns with no fixture line:\n".implode("\n", $dead));
    }

    public function test_category_patterns_are_valid_regexes()
    {
        foreach ((new Categories)->getAll() as $category => $patterns) {
            foreach ($patterns as $pattern) {
                $this->assertNotFalse(@preg_match('/'.$pattern.'/i', ''), "Invalid regex pattern in $category: $pattern");
            }
        }
    }

    public function test_category_patterns_are_unique_across_categories()
    {
        $all = [];

        foreach ((new Categories)->getAll() as $patterns) {
            $all = array_merge($all, $patterns);
        }

        $this->assertSame(count($all), count(array_unique($all)));
    }

    /**
     * A pattern broad enough to match a genuine browser is broad enough to
     * misclassify other crawlers, so hold category patterns to the same
     * standard as detection patterns.
     */
    public function test_category_patterns_do_not_match_devices()
    {
        $devices = file(__DIR__.'/data/user_agent/devices.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];

        foreach ((new Categories)->getAll() as $category => $patterns) {
            $hits = preg_grep('/'.$this->crawlerDetect->compileRegex($patterns).'/i', $devices);

            $this->assertSame([], array_values((array) $hits), "Category '$category' matches genuine device user agents");
        }
    }

    public function test_get_category_is_null_when_not_a_crawler()
    {
        $this->assertFalse($this->crawlerDetect->isCrawler('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36'));
        $this->assertNull($this->crawlerDetect->getCategory());
    }

    public function test_get_category_is_unknown_for_an_unclassified_crawler()
    {
        $this->assertTrue($this->crawlerDetect->isCrawler('somenaughtybot'));
        $this->assertSame(Categories::UNKNOWN, $this->crawlerDetect->getCategory());
        $this->assertSame('unknown', Categories::UNKNOWN);
    }

    public function test_get_category_follows_the_most_recent_check()
    {
        $this->crawlerDetect->isCrawler('Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');
        $this->assertSame('search', $this->crawlerDetect->getCategory());

        $this->crawlerDetect->isCrawler('Mozilla/5.0 (compatible; GPTBot/1.2; +https://openai.com/gptbot)');
        $this->assertSame('ai-training', $this->crawlerDetect->getCategory());

        $this->crawlerDetect->isCrawler('nothing to see here');
        $this->assertNull($this->crawlerDetect->getCategory());
    }

    public function test_get_category_uses_headers_when_no_agent_is_passed()
    {
        $cd = new CrawlerDetect([
            'User-Agent' => 'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)',
        ]);

        $this->assertTrue($cd->isCrawler());
        $this->assertSame('search', $cd->getCategory());
    }

    public function test_more_specific_ai_categories_win_over_search()
    {
        $this->crawlerDetect->isCrawler('Mozilla/5.0 (compatible; Applebot-Extended/0.1; +http://www.apple.com/go/applebot)');
        $this->assertSame('ai-training', $this->crawlerDetect->getCategory());

        $this->crawlerDetect->isCrawler('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko; compatible; Applebot/0.1; +http://www.apple.com/go/applebot)');
        $this->assertSame('search', $this->crawlerDetect->getCategory());
    }

    /**
     * @return string
     */
    private function fixturePath($category)
    {
        return __DIR__."/data/categories/$category.txt";
    }

    /**
     * @return array<int, string>
     */
    private function fixtureLines($category)
    {
        $path = $this->fixturePath($category);

        $this->assertFileExists($path);

        return file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
    }
}
