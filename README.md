CrawlerDetect
=======
[![Build Status](https://img.shields.io/travis/JayBizzle/Crawler-Detect.svg?style=flat-square)](https://travis-ci.org/JayBizzle/Crawler-Detect) [![Total Downloads](https://img.shields.io/packagist/dt/JayBizzle/Crawler-Detect.svg?style=flat-square)](https://packagist.org/packages/jaybizzle/crawler-detect)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/JayBizzle/Crawler-Detect.svg?style=flat-square)](https://scrutinizer-ci.com/g/JayBizzle/Crawler-Detect/?branch=master) [![MIT](https://img.shields.io/badge/license-MIT-ff69b4.svg?style=flat-square)](https://github.com/JayBizzle/Crawler-Detect)

CrawlerDetect is a PHP class for detecting bots/crawlers/spiders via the user agent

### Instalation
Run `` or add `"jaybizzle/crawler-detect" :"1.*"` to your `composer.json`.

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

// Output which regex rules matched (if any)
var_dump($CrawlerDetect->getMatches());
```

### Laravel Package
If you would like to use this with Laravel 5, please see [Laravel-Crawler-Detect](https://github.com/JayBizzle/Laravel-Crawler-Detect)

### Changelog
**v1.0.1**
Added ['Yahoo Link Preview'](https://help.yahoo.com/kb/mail/yahoo-link-preview-SLN23615.html) bot
