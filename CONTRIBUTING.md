### Contributing
If you find a bot/spider/crawler user agent that CrawlerDetect fails to detect, please submit a pull request with
- regex pattern added to the `$data` array in `src/Fixtures/Crawlers.php`
- the failing user agent string added to `tests/data/user_agent/crawlers.txt`

The `raw/Crawlers.json` and `raw/Crawlers.txt` files are regenerated automatically by `export.php` after each merge to `master` — you do not need to update them by hand.

### Real browser user agents
`tests/data/user_agent/devices.txt` and `tests/data/sec_ch_ua/devices.txt` hold real browser traffic, and the test suite checks none of it is flagged. If you have User-Agents from real visitors, especially recent browsers, in-app webviews or regions that aren't well covered yet, please share them using the "Contribute real browser user agents" issue form.

The form takes either a `.txt` file with one User-Agent per line, or a `.csv` file with the User-Agent in the first column and, optionally, the client hint headers as a JSON object in the second.

To import a submission:

```
composer import-devices -- path/to/file --dry-run
composer import-devices -- path/to/file
```

New entries are appended in the order given, and existing entries are never removed. Anything CrawlerDetect flags is listed instead of being added; each one is either a false positive to fix in `src/Fixtures/Crawlers.php` or `src/Fixtures/Exclusions.php`, or something that shouldn't have been in the submission.
