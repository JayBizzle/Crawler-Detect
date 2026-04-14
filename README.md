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

## Contributing

If you find a bot, spider or crawler that CrawlerDetect fails to detect, please open a pull request that:

- adds the regex pattern to the `$data` array in `src/Fixtures/Crawlers.php` and to the raw files `raw/Crawlers.json` and `raw/Crawlers.txt`
- adds the failing user agent string to `tests/crawlers.txt`

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
