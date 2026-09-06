<p align="center">
  <a href="https://crawlerdetect.io/" target="_blank">
    <img src="https://crawlerdetect.io/og-image.png" width="100%" alt="CrawlerDetect" />
  </a>
</p>

<p align="center">
  <a href="https://crawlerdetect.io/" target="_blank"><strong>crawlerdetect.io</strong></a>
</p>

<p align="center">
  <a href="https://github.com/JayBizzle/Crawler-Detect/actions"><img alt="Build Status" src="https://img.shields.io/github/actions/workflow/status/JayBizzle/Crawler-Detect/test.yml?branch=master&style=flat-square"></a>
  <a href="https://packagist.org/packages/jaybizzle/crawler-detect"><img alt="Downloads" src="https://img.shields.io/packagist/dm/JayBizzle/Crawler-Detect.svg?style=flat-square" /></a>
  <a href="https://packagist.org/packages/jaybizzle/crawler-detect"><img alt="Latest Version" src="https://img.shields.io/packagist/v/jaybizzle/Crawler-Detect.svg?style=flat-square" /></a>
  <a href="https://coveralls.io/github/JayBizzle/Crawler-Detect"><img alt="Coverage" src="https://img.shields.io/coveralls/JayBizzle/Crawler-Detect/master.svg?style=flat-square" /></a>
  <a href="https://github.com/JayBizzle/Crawler-Detect/blob/master/LICENSE"><img alt="License" src="https://img.shields.io/badge/license-MIT-ff69b4.svg?style=flat-square" /></a>
</p>

## About

**CrawlerDetect** is a PHP library for detecting bots, crawlers and spiders via the `User-Agent` and `HTTP_FROM` headers. It currently recognises thousands of user agents and is updated regularly.

## Installation

```bash
composer require jaybizzle/crawler-detect
```

## Usage

```php
use Jaybizzle\CrawlerDetect\CrawlerDetect;

$CrawlerDetect = new CrawlerDetect;

// Check the user agent of the current visitor
if ($CrawlerDetect->isCrawler()) {
    // true if a crawler user agent was detected
}

// Pass a user agent as a string
if ($CrawlerDetect->isCrawler('Mozilla/5.0 (compatible; Sosospider/2.0; +http://help.soso.com/webspider.htm)')) {
    // true if a crawler user agent was detected
}

// Output the name of the bot that matched (if any)
echo $CrawlerDetect->getMatches();
```

### Classifying the crawler

Not every crawler deserves the same treatment. You may want to let search engines and social link previews through, block AI training crawlers, and let AI assistants fetching on behalf of a real person through. After a positive `isCrawler()` check, `getCategory()` tells you what kind of crawler it was:

```php
if ($CrawlerDetect->isCrawler($userAgent)) {
    switch ($CrawlerDetect->getCategory()) {
        case 'search':
        case 'social':
        case 'ai-user':
            // let it through
            break;
        case 'ai-training':
            // block, throttle, or serve a summary
            break;
    }
}
```

| Category | What it covers | Examples |
| --- | --- | --- |
| `ai-user` | Fetches made on behalf of a live user by an AI assistant or browser agent | ChatGPT-User, Claude-User, Perplexity-User, Google-Agent |
| `ai-search` | Crawlers that index content so an AI product can search or cite it | OAI-SearchBot, PerplexityBot, Claude-SearchBot |
| `ai-training` | Crawlers collecting data to train AI models | GPTBot, ClaudeBot, Bytespider, CCBot, meta-externalagent |
| `search` | Search engine indexers and their related fetchers | Googlebot, bingbot, DuckDuckBot, Applebot, Baiduspider |
| `social` | Link preview fetchers of social networks and chat apps | facebookexternalhit, Twitterbot, LinkedInBot, Slackbot |
| `feed` | Feed readers, aggregators and podcast apps | Feedly, Feedbin, NewsBlur, Inoreader |
| `seo` | SEO, backlink and marketing intelligence crawlers | AhrefsBot, SemrushBot, MJ12bot, Screaming Frog |
| `monitoring` | Uptime, performance and site-health tools | UptimeRobot, Pingdom, StatusCake, Chrome-Lighthouse |
| `security` | Vulnerability and internet-wide scanners | Nuclei, zgrab, Expanse, Censys |
| `archiver` | Web archives | ia_archiver, archive.org_bot, heritrix |
| `scraper` | Offline downloaders and scraping frameworks | HTTrack, Scrapy, WebCopier |
| `headless` | Headless browsers and automation frameworks | HeadlessChrome, PhantomJS |
| `http-library` | Programmatic HTTP clients and language runtimes | curl, python-requests, Go-http-client, okhttp |

A crawler that fits none of these reports `unknown`. When the last check was not a crawler, `getCategory()` returns `null`. Categories are checked in the order listed and the first match wins, which is why the AI categories sit above `search` (so Applebot-Extended is not reported as Applebot) and `http-library` sits last (many bots mention the library they are built on). `getCategories()` returns the list of names.

Classification only runs after a positive match and only when you ask for it, so existing `isCrawler()` callers pay nothing extra.

### Passing headers from a request object

With no arguments, CrawlerDetect reads `$_SERVER`. If your headers come from somewhere else — a PSR-7 request, Symfony's `HeaderBag`, Swoole, or a Lambda event — pass them in directly. Both real header names (`User-Agent`) and PHP's SAPI names (`HTTP_USER_AGENT`) are understood, and values may be strings or arrays of strings.

```php
// PSR-7 (Slim, Mezzio, Laminas, League)
$CrawlerDetect = new CrawlerDetect($request->getHeaders());

// Symfony HttpFoundation
$CrawlerDetect = new CrawlerDetect($request->headers->all());

// Swoole
$CrawlerDetect = new CrawlerDetect($request->header);

if ($CrawlerDetect->isCrawler()) {
    // ...
}
```

Prefer this over `isCrawler($request->getHeaderLine('User-Agent'))`. Some crawlers — Googlebot in particular — send a genuine browser `User-Agent` and identify themselves in another header such as `From` or `Sec-CH-UA`. Passing the full set lets CrawlerDetect check all of them; passing a single string can only ever check one.

## Contributing

If you find a bot, spider or crawler that CrawlerDetect fails to detect, please open a pull request that:

- adds the regex pattern to the `$data` array in `src/Fixtures/Crawlers.php`
- adds the failing user agent string to `tests/data/user_agent/crawlers.txt`
- optionally, if the bot fits a category in `src/Fixtures/Categories.php`, adds a pattern there and the same user agent string to `tests/data/categories/<category>.txt`

The `raw/Crawlers.json`, `raw/Crawlers.txt` and `raw/Categories.json` files are regenerated automatically by `export.php` after merge — no need to touch them.

If you're not able to submit a PR, open an issue with the user agent string and we'll take it from there.

## Ports & Integrations

CrawlerDetect has been ported to a number of other languages and frameworks. If you maintain a port not listed here, please open a PR.

| Platform | Project |
| --- | --- |
| Laravel | [Laravel-Crawler-Detect](https://github.com/JayBizzle/Laravel-Crawler-Detect) |
| Symfony 2 / 3 / 4 | [CrawlerDetectBundle](https://github.com/nicolasmure/CrawlerDetectBundle) |
| Yii2 | [yii2-crawler-detect](https://github.com/AlikDex/yii2-crawler-detect) |
| Node.js / ES6 | [es6-crawler-detect](https://github.com/JefferyHus/es6-crawler-detect) |
| Python | [crawlerdetect](https://github.com/moskrc/CrawlerDetect) |
| JVM (Java, Scala, Kotlin) | [CrawlerDetect](https://github.com/nekosoftllc/crawler-detect) |
| .NET / .NET Core | [NetCrawlerDetect](https://github.com/gplumb/NetCrawlerDetect) |
| Ruby | [crawler_detect](https://github.com/loadkpi/crawler_detect) |
| Go | [crawlerdetect](https://github.com/x-way/crawlerdetect) |

## Credits

Parts of this library are based on the excellent [MobileDetect](https://github.com/serbanghita/Mobile-Detect).

## License

Released under the [MIT License](LICENSE).
