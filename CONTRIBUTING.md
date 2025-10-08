### Contributing
If you find a bot/spider/crawler user agent that CrawlerDetect fails to detect, please submit a pull request with 
- regex pattern added to the `$data` array in `Fixtures/Crawlers.php` and to the raw files `raw/Crawlers.json` and `raw/Crawlers.txt`
- add the failing user agent to `tests/crawlers.txt`.
