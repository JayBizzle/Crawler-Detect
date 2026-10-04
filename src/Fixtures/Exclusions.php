<?php

/*
 * This file is part of Crawler Detect - the web crawler detection library.
 *
 * (c) Mark Beech <m@rkbee.ch>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jaybizzle\CrawlerDetect\Fixtures;

class Exclusions extends AbstractProvider
{
    /**
     * Strings removed from the user agent before the crawler regex runs.
     *
     * The list does two different jobs, so it is kept in two sections.
     *
     * The first section strips the browser and platform tokens that make up
     * most of an ordinary user agent. The crawler regex is a large unanchored
     * alternation, so its cost grows with the length of the string; removing
     * those tokens makes a non-matching check roughly three times faster.
     * Every entry here must be specific enough never to eat the character
     * after the token. A wildcard '.' after 'Firefox' once turned a
     * hypothetical 'FirefoxBot' into 'ot', and ' Intel' without a trailing
     * space turned 'Name Intelligence' into 'Nameligence'. The
     * test_exclusions_do_not_damage_signatures test guards against this.
     *
     * The second section is not about speed at all. It removes device and app
     * names that happen to contain 'bot' or another catch-all word, so that a
     * real phone such as a Cubot handset is not reported as a crawler. Removing
     * one of these entries changes detection results, not just timing.
     *
     * @var array
     */
    protected $data = [
        // Browser and platform tokens, stripped for speed.
        'Safari\/[\d\.]*',
        'Firefox\/[\d\.]*',
        ' Chrome\/[\d\.]*',
        'Chromium\/[\d\.]*',
        'MSIE [\d\.]*',
        'Opera\/[\d\.]*',
        'Mozilla\/[\d\.]*',
        'AppleWebKit\/[\d\.]*',
        'Trident\/[\d\.]*',
        'Windows NT [\d\.]*',
        'Android [\d\.]*',
        'Macintosh;',
        'Ubuntu',
        'Linux',
        ' Intel ',
        'Mac OS X [\d_]*',
        '(like )?Gecko(\/[\d\.]*)?',
        'KHTML,',
        'CriOS\/[\d\.]*',
        'CPU iPhone OS ([0-9_])* like Mac OS X',
        'CPU OS ([0-9_])* like Mac OS X',
        'iPod',
        'compatible',
        'x86_..',
        'i686',
        'x64',
        'X11',
        'rv:[\d\.]*',
        'Version\/[\d\.]*',
        'WOW64',
        'Win64',
        'Dalvik\/[\d\.]*',
        ' \.NET CLR [\d\.]*',
        'Presto\/[\d\.]*',
        'Media Center PC',
        'BlackBerry',
        'Build\/',
        'Opera Mini\/\d{1,2}\.\d{1,2}\.[\d\.]*\/\d{1,2}\.',
        'Opera',
        ' \.NET[\d\.]*',

        // Device and app names that contain a catch-all word. These prevent
        // false positives and are not performance related.
        'cubot',
        '; M bot',
        '; CRONO',
        '; B bot',
        '; IDbot',
        '; ID bot',
        '; POWER BOT',
        'OCTOPUS-CORE',
        'htc_botdugls',
        'super\/\d+\/Android\/\d+',
        '"Yandex"',
        'YandexModule2',
    ];
}
