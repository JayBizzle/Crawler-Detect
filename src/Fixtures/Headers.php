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

class Headers extends AbstractProvider
{
    /**
     * All possible HTTP headers that represent the user agent string.
     *
     * @var array
     */
    protected $data = array(
        // The default User-Agent string.
        'HTTP_USER_AGENT',
        // Header can occur on devices using Opera Mini.
        'HTTP_X_OPERAMINI_PHONE_UA',
        // Vodafone specific header: http://www.seoprinciple.com/mobile-web-community-still-angry-at-vodafone/24/
        'HTTP_X_DEVICE_USER_AGENT',
        'HTTP_X_ORIGINAL_USER_AGENT',
        'HTTP_X_SKYFIRE_PHONE',
        'HTTP_X_BOLT_PHONE_UA',
        'HTTP_DEVICE_STOCK_UA',
        'HTTP_X_UCBROWSER_DEVICE_UA',
        // Sometimes, bots (especially Google) use a genuine user agent, but fill this header in with their email address
        'HTTP_FROM',
        'HTTP_X_SCANNER', // Seen in use by Netsparker
        'HTTP_X_PURPOSE', // Prefetch Header
        'HTTP_PURPOSE', // Prefetch Header
    );

    /**
     * Headers used for prefetching webpages by different services.
     *
     * @var array
     */
    protected $prefetchHeaders = array(
        'HTTP_X_PURPOSE' => 'preview', // Used by Facebook to prefetch, as well the previews seen when opening a new tab in Chrome and Safari
        'HTTP_PURPOSE' => 'prefetch', // Seen with value prefetch
    );

    /**
     * Return the data set.
     *
     * @return array
     */
    public function getPrefetchHeaders()
    {
        return $this->prefetchHeaders;
    }
}
