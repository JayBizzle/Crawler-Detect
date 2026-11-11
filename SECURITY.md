# Security Policy

## Supported versions

Fixes are released for the latest 1.x version only. If you are on an older release, please upgrade to the latest version before reporting.

## Reporting a vulnerability

Please don't open a public issue for a security problem. Report it privately through GitHub instead:

https://github.com/JayBizzle/Crawler-Detect/security/advisories/new

Include the version you're using, your PHP version and whether the PCRE JIT is enabled (`php -i | grep pcre.jit`), and a minimal way to reproduce the problem.

I'll acknowledge the report within a week and keep you updated while a fix is prepared. Once a fix is released I'll publish an advisory and credit you, unless you'd rather not be named.

## Scope

CrawlerDetect classifies user agents. A crawler that goes undetected, or a real browser that is flagged as a crawler, is a detection bug rather than a security issue, so please report those through the normal issue templates.

Things that are in scope include input that makes detection slow enough to tie up a request (for example catastrophic regex backtracking), and anything that causes an error or crash from request data.
