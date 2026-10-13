### Contributing
If you find a bot/spider/crawler user agent that CrawlerDetect fails to detect, please submit a pull request with
- regex pattern added to the `$data` array in `src/Fixtures/Crawlers.php`
- the failing user agent string added to `tests/data/user_agent/crawlers.txt`

If the bot fits one of the categories in `src/Fixtures/Categories.php` (search engine, AI, SEO tool, uptime monitor and so on), also add a pattern there and the same user agent string to `tests/data/categories/<category>.txt`. This is optional: an uncategorised bot simply reports `unknown`.

The `raw/Crawlers.json` and `raw/Crawlers.txt` files are regenerated automatically by `export.php` after each merge to `master` — you do not need to update them by hand.
