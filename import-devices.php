<?php

/*
 * This file is part of Crawler Detect - the web crawler detection library.
 *
 * (c) Mark Beech <m@rkbee.ch>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/*
 * Imports contributed real browser traffic into the device test data.
 *
 *   composer import-devices -- path/to/file [--dry-run]
 *
 * A .csv file has the User-Agent in the first column and, optionally, the
 * client hint headers as a JSON object in the second. Any other file is read
 * as one User-Agent per line.
 *
 * Entries already in the test data are skipped. Anything CrawlerDetect flags
 * is listed for review instead of being added, since it's either a false
 * positive to fix or something that shouldn't have been in the submission.
 */

if (PHP_VERSION_ID < 70400) {
    fwrite(STDERR, "This script needs PHP 7.4 or later.\n");
    exit(1);
}

require __DIR__.'/vendor/autoload.php';

use Jaybizzle\CrawlerDetect\CrawlerDetect;

$args = array_slice($argv, 1);
$dryRun = in_array('--dry-run', $args, true);
$args = array_values(array_diff($args, ['--dry-run']));

if (count($args) !== 1 || ! is_readable($args[0])) {
    fwrite(STDERR, "Usage: composer import-devices -- path/to/file [--dry-run]\n");
    exit(1);
}

[$userAgents, $secChUa, $warnings] = readSubmission($args[0]);

foreach ($warnings as $warning) {
    fwrite(STDERR, "Warning: $warning\n");
}

$crawlerDetect = new CrawlerDetect;
$flagged = [];

$targets = [
    'User-Agent' => [__DIR__.'/tests/data/user_agent/devices.txt', $userAgents],
    'Sec-CH-UA' => [__DIR__.'/tests/data/sec_ch_ua/devices.txt', $secChUa],
];

foreach ($targets as $label => [$path, $entries]) {
    $existing = file_exists($path) ? file_get_contents($path) : '';
    $known = array_flip(explode("\n", str_replace("\r\n", "\n", $existing)));
    $new = [];
    $duplicates = 0;

    foreach ($entries as $entry) {
        if (isset($known[$entry])) {
            $duplicates++;
            continue;
        }

        $known[$entry] = true;

        if ($crawlerDetect->isCrawler($entry)) {
            $flagged[] = [$label, $crawlerDetect->getMatches(), $entry];
            continue;
        }

        $new[] = $entry;
    }

    printf("%s: %d new, %d already present, %s\n", $label, count($new), $duplicates, substr($path, strlen(__DIR__) + 1));

    if ($new && ! $dryRun) {
        $prefix = ($existing === '' || substr($existing, -1) === "\n") ? '' : "\n";
        file_put_contents($path, $prefix.implode("\n", $new)."\n", FILE_APPEND);
    }
}

if ($flagged) {
    printf("\n%d flagged as crawlers and not added. Check each one:\n", count($flagged));

    foreach ($flagged as [$label, $match, $entry]) {
        printf("  [%s] matched \"%s\": %s\n", $label, $match, $entry);
    }
}

if ($dryRun) {
    echo "\nDry run, nothing was written.\n";
}

function readSubmission($path)
{
    $userAgents = [];
    $secChUa = [];
    $warnings = [];
    $isCsv = strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'csv';
    $handle = fopen($path, 'r');
    $row = 0;

    while (($fields = readRow($handle, $isCsv)) !== null) {
        $row++;
        $userAgent = trim($fields[0] ?? '');

        if ($userAgent === '' || ($row === 1 && preg_match('/^user[-_ ]?agent$/i', $userAgent))) {
            continue;
        }

        if (preg_match('/[\x00-\x1f\x7f]/', $userAgent)) {
            $warnings[] = "row $row has control characters, skipped";
            continue;
        }

        $userAgents[$userAgent] = true;

        $hints = trim($fields[1] ?? '');

        if ($hints === '') {
            continue;
        }

        $decoded = json_decode($hints, true);

        if (! is_array($decoded)) {
            $warnings[] = "row $row has client hints that aren't a JSON object, ignored";
            continue;
        }

        foreach ($decoded as $header => $value) {
            if (strcasecmp($header, 'Sec-CH-UA') === 0 && is_string($value) && trim($value) !== '') {
                $secChUa[trim($value)] = true;
            }
        }
    }

    fclose($handle);

    return [array_keys($userAgents), array_keys($secChUa), $warnings];
}

function readRow($handle, $isCsv)
{
    if ($isCsv) {
        $fields = fgetcsv($handle, 0, ',', '"', '');

        return $fields === false ? null : $fields;
    }

    $line = fgets($handle);

    return $line === false ? null : [rtrim($line, "\r\n")];
}
