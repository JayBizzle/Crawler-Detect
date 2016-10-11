CrawlerDetect
=======
[![Build Status](https://img.shields.io/travis/JayBizzle/Crawler-Detect/master.svg?style=flat-square)](https://travis-ci.org/JayBizzle/Crawler-Detect) [![Total Downloads](https://img.shields.io/packagist/dt/JayBizzle/Crawler-Detect.svg?style=flat-square)](https://packagist.org/packages/jaybizzle/crawler-detect)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/JayBizzle/Crawler-Detect.svg?style=flat-square)](https://scrutinizer-ci.com/g/JayBizzle/Crawler-Detect/?branch=master) [![MIT](https://img.shields.io/badge/license-MIT-ff69b4.svg?style=flat-square)](https://github.com/JayBizzle/Crawler-Detect) [![Version](https://img.shields.io/packagist/v/jaybizzle/Crawler-Detect.svg?style=flat-square)](https://packagist.org/packages/jaybizzle/crawler-detect) [![StyleCI](https://styleci.io/repos/32755917/shield)](https://styleci.io/repos/32755917)
[![Coveralls branch](https://img.shields.io/coveralls/JayBizzle/Crawler-Detect/master.svg?style=flat-square)](https://coveralls.io/github/JayBizzle/Crawler-Detect)

CrawlerDetect is a PHP class for detecting bots/crawlers/spiders via the user agent and http_from header. Currently able to detect 100's of bots/spiders/crawlers.

### Installation
Run `composer require jaybizzle/crawler-detect 1.*` or add `"jaybizzle/crawler-detect" :"1.*"` to your `composer.json`.

### Usage
```PHP
use Jaybizzle\CrawlerDetect\CrawlerDetect;

$CrawlerDetect = new CrawlerDetect;

// Check the user agent of the current 'visitor'
if($CrawlerDetect->isCrawler()) {
	// true if crawler user agent detected
}

// Pass a user agent as a string
if($CrawlerDetect->isCrawler('Mozilla/5.0 (compatible; Sosospider/2.0; +http://help.soso.com/webspider.htm)')) {
	// true if crawler user agent detected
}

// Output the name of the bot that matched (if any)
echo $CrawlerDetect->getMatches();
```

### Contributing
If you find a bot/spider/crawler user agent that CrawlerDetect fails to detect, please submit a pull request with the regex pattern added to the `$data` array in `Fixtures/Crawlers.php` and add the failing user agent to `tests/crawlers.txt`.

Failing that, just create an issue with the user agent you have found, and we'll take it from there :)

### Laravel Package
If you would like to use this with Laravel 4/5, please see [Laravel-Crawler-Detect](https://github.com/JayBizzle/Laravel-Crawler-Detect)

_Parts of this class are based on the brilliant [MobileDetect](https://github.com/serbanghita/Mobile-Detect)_

[![Analytics](https://ga-beacon.appspot.com/UA-72430465-1/Crawler-Detect/readme?pixel)](https://github.com/JayBizzle/Crawler-Detect)
