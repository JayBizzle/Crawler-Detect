<?php

namespace Jaybizzle\CrawlerDetect;

class CrawlerDetect
{
    /**
     * The user agent.
     * 
     * @var null
     */
    protected $userAgent = null;

    /**
     * Headers that contain a user agent.
     * 
     * @var array
     */
    protected $httpHeaders = array();

    /**
     * Store regex matches.
     * 
     * @var array
     */
    protected $matches = array();

    /**
     * List of strings to remove from the user agent before running the crawler regex
     * Over a large list of user agents, this gives us about a 55% speed increase!
     * 
     * @var array
     */
    protected static $ignore = array(
        'Safari.[\d\.]*',
        'Firefox.[\d\.]*',
        'Chrome.[\d\.]*',
        'Chromium.[\d\.]*',
        'MSIE.[\d\.]',
        'Opera\/[\d\.]*',
        'Mozilla.[\d\.]*',
        'AppleWebKit.[\d\.]*',
        'Trident.[\d\.]*',
        'Windows NT.[\d\.]*',
        'Android.[\d\.]*',
        'Macintosh.',
        'Ubuntu',
        'Linux',
        'Intel',
        'Mac OS X',
        'Gecko.[\d\.]*',
        'KHTML',
        'CriOS.[\d\.]*',
        'CPU iPhone OS ([0-9_])* like Mac OS X',
        'CPU OS ([0-9_])* like Mac OS X',
        'iPod',
        'like Gecko',
        'compatible',
        'x86_..',
        'i686',
        'x64',
        'X11',
        'rv:[\d\.]*',
        'Version.[\d\.]*',
        'WOW64',
        'Win64',
        'Dalvik.[\d\.]*',
        '\.NET CLR [\d\.]*',
        'Presto.[\d\.]*',
        'Media Center PC',
    );

    /**
     * Array of regular expressions to match against the user agent.
     * 
     * @var array
     */
    protected static $crawlers = array(
        '008\\/',
        'A6-Indexer',
        'Aboundex',
        'Accoona-AI-Agent',
        'acoon',
        'AddThis',
        'ADmantX',
        'AHC',
        'Airmail',
        'Anemone',
        'Arachmo',
        'archive-com',
        'B-l-i-t-z-B-O-T',
        'bibnum\.bnf',
        'biglotron',
        'binlar',
        'BingPreview',
        'boitho\.com-dc',
        'BUbiNG',
        'Butterfly\\/',
        'BuzzSumo',
        'CC Metadata Scaper',
        'Cerberian Drtrs',
        'changedetection',
        'Charlotte',
        'clips\.ua\.ac\.be',
        'CloudFlare-AlwaysOnline',
        'coccoc',
        'Commons-HttpClient',
        'convera',
        'cosmos',
        'Covario-IDS',
        'curl',
        'CyberPatrol',
        'DataparkSearch',
        'dataprovider',
        'Digg',
        'DomainAppender',
        'drupact',
        'EARTHCOM',
        'ec2linkfinder',
        'ElectricMonk',
        'Embedly',
        'europarchive\.org',
        'EventMachine HttpClient',
        'ezooms',
        'eZ Publish Link Validator',
        'facebookexternalhit',
        'Feedfetcher-Google',
        'FeedValidator',
        'FindLinks',
        'findlink',
        'findthatfile',
        'Flamingo_SearchEngine',
        'fluffy',
        'getprismatic\.com',
        'g00g1e\.net',
        'GigablastOpenSource',
        'grub-client',
        'Genieo',
        'Go-http-client',
        'Google-HTTP-Java-Client',
        'Google favicon',
        'Google Keyword Suggestion',
        'heritrix',
        'Holmes',
        'htdig',
        'httpunit',
        'httrack',
        'ichiro',
        'igdeSpyder',
        'InAGist',
        'InfoWizards Reciprocal Link System PRO',
        'integromedb',
        'IODC',
        'IOI',
        'ips-agent',
        'iZSearch',
        'L\.webis',
        'Larbin',
        'libwww',
        'Link Valet',
        'linkdex',
        'LinkExaminer',
        'LinkWalker',
        'Lipperhey Link Explorer',
        'Lipperhey SEO Service',
        'LongURL API',
        'ltx71',
        'lwp-trivial',
        'MegaIndex\.ru',
        'mabontland',
        'MagpieRSS',
        'Mediapartners-Google',
        'MetaURI',
        'Mnogosearch',
        'mogimogi',
        'Morning Paper',
        'Mrcgiguy',
        'MVAClient',
        'netresearchserver',
        'NewsGator',
        'newsme',
        'NG-Search',
        '^NING\\/',
        'Notifixious',
        'nutch',
        'NutchCVS',
        'Nymesis',
        'oegp',
        'online link validator',
        'Online Website Link Checker',
        'Orbiter',
        'ow\.ly',
        'Ploetz \+ Zeller',
        'page2rss',
        'panscient',
        'Peew',
        'phpcrawl',
        'Pizilla',
        'Plukkie',
        'Pompos',
        'postano',
        'PostPost',
        'postrank',
        'proximic',
        'PycURL',
        'Python-httplib2',
        'python-requests',
        'Python-urllib',
        'Qseero',
        'Qwantify',
        'Radian6',
        'RebelMouse',
        'REL Link Checker',
        'RetrevoPageAnalyzer',
        'Riddler',
        'Robosourcer',
        'Ruby',
        'SBIder',
        'ScoutJet',
        'ScoutURLMonitor',
        'Scrapy',
        'Scrubby',
        'SearchSight',
        'semanticdiscovery',
        'SEOstats',
        'Seznam screenshot-generator',
        'ShopWiki',
        'SiteBar',
        'siteexplorer\.info',
        'slider\.com',
        'slurp',
        'Snappy',
        'sogou',
        'speedy',
        'Sqworm',
        'StackRambler',
        'Stratagems Kumo',
        'summify',
        'teoma',
        'theoldreader\.com',
        'TinEye',
        'Traackr.com',
        'truwoGPS',
        'tweetedtimes\.com',
        'Twikle',
        'UnwindFetchor',
        'updated',
        'urlresolver',
        'Validator\.nu\\/LV',
        'Vagabondo',
        'Vivante Link Checker',
        'Vortex',
        'voyager\\/',
        'VYU2',
        'W3C-checklink',
        'W3C_CSS_Validator_JFouffa',
        'W3C_I18n-Checker',
        'W3C-mobileOK',
        'W3C_Unicorn',
        'W3C_Validator',
        'WebIndex',
        'Websquash\.com',
        'webcollage',
        'webmon ',
        'WeSEE:Search',
        'wf84',
        'wget',
        'WomlpeFactory',
        'wotbox',
        'Xenu Link Sleuth',
        'XML Sitemaps Generator',
        'Y!J-ASR',
        'yacy',
        'Yahoo Link Preview',
        'Yahoo! Slurp China',
        'Yahoo! Slurp',
        'YahooSeeker',
        'YahooSeeker-Testing',
        'YandexImages',
        'YandexMetrika',
        'yandex',
        'yanga',
        'yeti',
        'yoogliFetchAgent',
        'Zao',
        'ZyBorg',
        '[a-z0-9\\-_]*((?<!cu)bot|crawler|archiver|transcoder|spider)',
    );

    /**
     * All possible HTTP headers that represent the
     * User-Agent string.
     *
     * @var array
     */
    protected static $uaHttpHeaders = array(
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
    );

    /**
     * Class constructor.
     */
    public function __construct(array $headers = null, $userAgent = null)
    {
        $this->setHttpHeaders($headers);
        $this->setUserAgent($userAgent);
    }

    /**
     * Set HTTP headers.
     * 
     * @param array $httpHeaders
     */
    public function setHttpHeaders($httpHeaders = null)
    {
        // use global _SERVER if $httpHeaders aren't defined
        if (!is_array($httpHeaders) || !count($httpHeaders)) {
            $httpHeaders = $_SERVER;
        }
        // clear existing headers
        $this->httpHeaders = array();
        // Only save HTTP headers. In PHP land, that means only _SERVER vars that
        // start with HTTP_.
        foreach ($httpHeaders as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $this->httpHeaders[$key] = $value;
            }
        }
    }

    /**
     * Return user agent headers.
     * 
     * @return array
     */
    public function getUaHttpHeaders()
    {
        return self::$uaHttpHeaders;
    }

    /**
     * Set the user agent.
     * 
     * @param string $userAgent
     */
    public function setUserAgent($userAgent = null)
    {
        if (false === empty($userAgent)) {
            return $this->userAgent = $userAgent;
        } else {
            $this->userAgent = null;
            foreach ($this->getUaHttpHeaders() as $altHeader) {
                if (false === empty($this->httpHeaders[$altHeader])) { // @todo: should use getHttpHeader(), but it would be slow.
                    $this->userAgent .= $this->httpHeaders[$altHeader].' ';
                }
            }

            return $this->userAgent = (!empty($this->userAgent) ? trim($this->userAgent) : null);
        }
    }

    /**
     * Build the user agent regex.
     * 
     * @return string
     */
    public function getRegex()
    {
        return '('.implode('|', self::$crawlers).')';
    }

    /**
     * Build the replacement regex.
     * 
     * @return string
     */
    public function getIgnored()
    {
        return '('.implode('|', self::$ignore).')';
    }

    /**
     * Check user agent string against the regex.
     * 
     * @param string $userAgent
     *
     * @return bool
     */
    public function isCrawler($userAgent = null)
    {
        $agent = is_null($userAgent) ? $this->userAgent : $userAgent;

        $agent = preg_replace('/'.$this->getIgnored().'/i', '', $agent);

        $result = preg_match('/'.$this->getRegex().'/i', $agent, $matches);

        if ($matches) {
            $this->matches = $matches;
        }

        return (bool) $result;
    }

    /**
     * Return the matches.
     * 
     * @return array
     */
    public function getMatches()
    {
        return $this->matches[0];
    }
}
