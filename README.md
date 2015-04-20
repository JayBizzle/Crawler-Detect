CrawlerDetect
=======
[![Build Status](https://img.shields.io/travis/JayBizzle/Crawler-Detect.svg?style=flat-square)](https://travis-ci.org/JayBizzle/Crawler-Detect) [![Total Downloads](https://img.shields.io/packagist/dt/JayBizzle/Crawler-Detect.svg?style=flat-square)](https://packagist.org/packages/jaybizzle/crawler-detect)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/JayBizzle/Crawler-Detect.svg?style=flat-square)](https://scrutinizer-ci.com/g/JayBizzle/Crawler-Detect/?branch=master) [![MIT](https://img.shields.io/badge/license-MIT-ff69b4.svg?style=flat-square)](https://github.com/JayBizzle/Crawler-Detect)

CrawlerDetect is a PHP class for detecting bots/crawlers/spiders via the user agent. Currently able to detect over 300 bots/spiders/crawlers.

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

// Output which regex rules matched (if any)
var_dump($CrawlerDetect->getMatches());
```

### Contributing
If you find a bot/spider/crawler user agent that CrawlerDetect fails to detect, please submit a pull request with with the regex pattern added to the `$crawlers` array in `CrawlerDetect.php` and add the failing user agent to `tests/crawlers.txt`.

Failing that, just create an issue with the user agent you have found, and we'll take it from there :)

### Laravel Package
If you would like to use this with Laravel 5, please see [Laravel-Crawler-Detect](https://github.com/JayBizzle/Laravel-Crawler-Detect)

### Changelog
**v1.0.2**
 - Added ['AbiLogicBot'](http://www.abilogic.com/bot.html)
 - Added ['Link Valet'](http://www.htmlhelp.com/tools/valet/)
 - Added ['Mrcgiguy'](http://www.w3dir.com/cgi-bin)
 - Added ['LinkExaminer'](http://www.analogx.com/contents/download/network/lnkexam/Freeware.htm)
 - Added ['LinksManager.com_bot'](http://www.linksmanager.com/)
 - Added ['Notifixious'](http://notifixio.us)
 - Added ['online link validator'](http://www.dead-links.com/)
 - Added ['Ploetz + Zeller'](http://www.ploetz-zeller.de)
 - Added ['InfoWizards Reciprocal Link System PRO'](http://www.infowizards.com)
 - Added REL Link Checker
 - Added ['SiteBar'](http://sitebar.org/)
 - Added ['Vivante Link Checker'](http://www.vivante.com)

**v1.0.1**
 - Added ['Yahoo Link Preview'](https://help.yahoo.com/kb/mail/yahoo-link-preview-SLN23615.html) bot

_Parts of this class are based on the brilliant [MobileDetect](https://github.com/serbanghita/Mobile-Detect)_
