<?php

/*
 * This file is part of Crawler Detect - the web crawler detection library.
 *
 * (c) Mark Beech <m@rkbee.ch>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use PHPUnit\Framework\TestCase;

final class RawDataConsistencyTest extends TestCase
{
    /** @test */
    public function raw_headers_are_consistent()
    {
        $this->assert_raw('Headers');
    }

    /** @test */
    public function raw_exclusions_are_consistent()
    {
        $this->assert_raw('Exclusions');
    }

    /** @test */
    public function raw_crawlers_are_consistent()
    {
        $this->assert_raw('Crawlers');
    }

    private function assert_raw($name)
    {
        $this->assertFileExists(__DIR__ . "/../raw/$name.txt");
        $this->assertFileExists(__DIR__ . "/../raw/$name.json");

        $txt = file(__DIR__ . "/../raw/$name.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $json = json_decode(file_get_contents(__DIR__ . "/../raw/$name.json"), true);

        $txt_missing = array_values(array_diff($json, $txt));
        $json_missing  = array_values(array_diff($txt, $json));
        $this->assertEmpty(
            array_merge($json_missing, $txt_missing),
            "JSON RAW Missing: " . json_encode($json_missing) . "; TXT RAW Missing: " . json_encode($txt_missing)
        );
    }
}
