<?php

namespace Jaybizzle\CrawlerDetect;

class CrawlerDetect
{
    protected $userAgent = null;

    protected $httpHeaders = [];

    protected $matches = [];

    protected static $crawlers = [
        '008\\/',
        '360Spider',
        'A6-Indexer',
        'ABACHOBot',
        'AbiLogicBot',
        'Aboundex',
        'Accoona-AI-Agent',
        'acoon',
        'AddSugarSpiderBot',
        'AddThis',
        'Adidxbot',
        'ADmantX',
        'AdvBot',
        'ahrefsbot',
        'aihitbot',
        'AISearchBot',
        'antibot',
        'AnyApexBot',
        'Applebot',
        'arabot',
        'Arachmo',
        'archive-com',
        'archive.org_bot',
        'B-l-i-t-z-B-O-T',
        'backlinkcrawler',
        'baiduspider',
        'BecomeBot',
        'BeslistBot',
        'bibnum\.bnf',
        'biglotron',
        'BillyBobBot',
        'Bimbot',
        'bingbot',
        'binlar',
        'blekkobot',
        'blexbot',
        'BlitzBOT',
        'bl\.uk_lddc_bot',
        'bnf\.fr_bot',
        'boitho\.com-dc',
        'boitho\.com-robot',
        'brainobot',
        'btbot',
        'BUbiNG',
        'buzzbot',
        'careerbot',
        'CatchBot',
        'CC Metadata Scaper',
        'ccbot',
        'Cerberian Drtrs',
        'changedetection',
        'Charlotte',
        'citeseerxbot',
        'coccoc',
        'classbot',
        'Commons-HttpClient',
        'content crawler spider',
        'convera',
        'ConveraCrawler',
        'CoPubbot',
        'cosmos',
        'Covario-IDS',
        'CrawlBot',
        'crawler4j',
        'CrystalSemanticsBot',
        'curl',
        'cXensebot',
        'CyberPatrol',
        'DataparkSearch',
        'dataprovider',
        'DiamondBot',
        'discobot',
        'domaincrawler',
        'Domain Re-Animator Bot',
        'dotbot',
        'drupact',
        'DuckDuckBot',
        'EARTHCOM',
        'ec2linkfinder',
        'edisterbot',
        'elisabot',
        'emailmarketingrobot',
        'EmeraldShield\.com WebBot',
        'envolk\[ITS\]spider',
        'EsperanzaBot',
        'europarchive\.org',
        'exabot',
        'ezooms',
        'facebookexternalhit',
        'Facebot',
        'FAST Enteprise Crawler',
        'FAST Enterprise Crawler',
        'FAST-WebCrawler',
        'FDSE robot',
        'FindLinks',
        'findlink',
        'findthatfile',
        'findxbot',
        'Flamingo_SearchEngine',
        'fluffy',
        'fr-crawler',
        'FurlBot',
        'FyberSpider',
        'g00g1e\.net',
        'GigablastOpenSource',
        'grub-client',
        'g2crawler',
        'Gaisbot',
        'GalaxyBot',
        'genieBot',
        'Genieo',
        'GermCrawler',
        'gigabot',
        'GingerCrawler',
        'Girafabot',
        'Gluten Free Crawler',
        'gnam gnam spider',
        'Googlebot-Image',
        'Googlebot-Mobile',
        'Googlebot',
        'GrapeshotCrawler',
        'gslfbot',
        'GurujiBot',
        'HappyFunBot',
        'Healthbot',
        'heritrix',
        'hl_ftien_spider',
        'Holmes',
        'htdig',
        'httpunit',
        'httrack',
        'ia_archiver',
        'iaskspider',
        'iCCrawler',
        'ichiro',
        'igdeSpyder',
        'iisbot',
        'InfoWizards Reciprocal Link System PRO',
        'Insitesbot',
        'integromedb',
        'intelium_bot',
        'InterfaxScanBot',
        'IOI',
        'ip-web-crawler\.com',
        'ips-agent',
        'IRLbot',
        'IssueCrawler',
        'IstellaBot',
        'it2media-domain-crawler',
        'Jaxified Bot',
        'JOC Web Spider',
        'jyxobot',
        'KoepaBot',
        'L\.webis',
        'LapozzBot',
        'Larbin',
        'lb-spider',
        'LDSpider',
        'LexxeBot',
        'libwww',
        'Linguee Bot',
        'Link Valet',
        'linkdex',
        'LinkExaminer',
        'LinksManager\.com_bot',
        'LinkpadBot',
        'LinkWalker',
        'Lipperhey SEO Service',
        'Livelapbot',
        'lmspider',
        'lssbot',
        'lssrocketcrawler',
        'lufsbot',
        'lwp-trivial',
        'Mail\.RU_Bot',
        'MegaIndex\.ru',
        'mabontland',
        'magpie-crawler',
        'Mediapartners-Google',
        'memorybot',
        'MJ12bot',
        'mlbot',
        'Mnogosearch',
        'mogimogi',
        'MojeekBot',
        'Moreoverbot',
        'Morning Paper',
        'Mrcgiguy',
        'msnbot',
        'msrbot',
        'MVAClient',
        'mxbot',
        'NerdByNature\.Bot',
        'NerdyBot',
        'netEstate NE Crawler',
        'netresearchserver',
        'NetSeer Crawler',
        'NewsGator',
        'NextGenSearchBot',
        'NG-Search',
        'ngbot',
        'nicebot',
        'niki-bot',
        'Notifixious',
        'noxtrumbot',
        'Nusearch Spider',
        'nutch',
        'NutchCVS',
        'Nymesis',
        'obot',
        'oegp',
        'omgilibot',
        'OmniExplorer_Bot',
        'online link validator',
        'OOZBOT',
        'openindexspider',
        'OrangeBot',
        'Orbiter',
        'PaperLiBot',
        'Pingdom\.com_bot',
        'Ploetz \+ Zeller',
        'page2rss',
        'PageBitesHyperBot',
        'panscient',
        'Peew',
        'phpcrawl',
        'Pizilla',
        'Plukkie',
        'polybot',
        'Pompos',
        'PostPost',
        'postrank',
        'proximic',
        'psbot',
        'purebot',
        'PycURL',
        'python-requests',
        'Python-urllib',
        'Qseero',
        'QuerySeekerSpider',
        'Qwantify',
        'Radian6',
        'RAMPyBot',
        'REL Link Checker',
        'RetrevoPageAnalyzer',
        'Riddler',
        'rogerbot',
        'RufusBot',
        'SandCrawler',
        'SBIder',
        'ScoutJet',
        'ScreenerBot',
        'scribdbot',
        'Scrubby',
        'SearchmetricsBot',
        'SearchSight',
        'seekbot',
        'semanticdiscovery',
        'SemrushBot',
        'Sensis Web Crawler',
        'SEOChat::Bot',
        'seokicks-robot',
        'SEOstats',
        'Seznam screenshot-generator',
        'seznambot',
        'Shim-Crawler',
        'ShopWiki',
        'Shoula robot',
        'ShowyouBot',
        'SimpleCrawler',
        'sistrix crawler',
        'SiteBar',
        'sitebot',
        'siteexplorer\.info',
        'SklikBot',
        'slider\.com',
        'slurp',
        'smtbot',
        'Snappy',
        'sogou spider',
        'sogou',
        'Sosospider',
        'spbot',
        'Speedy Spider',
        'speedy',
        'Sqworm',
        'StackRambler',
        'suggybot',
        'summify',
        'SurdotlyBot',
        'SurveyBot',
        'SynooBot',
        'tagoobot',
        'teoma',
        'TerrawizBot',
        'TheSuBot',
        'Thumbnail\.CZ robot',
        'TinEye',
        'toplistbot',
        'trendictionbot',
        'TrueBot',
        'truwoGPS',
        'turnitinbot',
        'TweetedTimes Bot',
        'TweetmemeBot',
        'twengabot',
        'Twitterbot',
        'uMBot',
        'UnisterBot',
        'updated',
        'urlappendbot',
        'Urlfilebot',
        'UsineNouvelleCrawler',
        'Vagabondo',
        'Vivante Link Checker',
        'voilabot',
        'Vortex',
        'voyager\\/',
        'VYU2',
        'web-archive-net\.com\.bot',
        'Websquash\.com',
        'WeSEE:Ads\/PageBot',
        'wbsearchbot',
        'webcollage',
        'webcompanycrawler',
        'webcrawler',
        'webmon ',
        'WeSEE:Search',
        'wf84',
        'wget',
        'wocbot',
        'WoFindeIch Robot',
        'WomlpeFactory',
        'woriobot',
        'wotbox',
        'Xaldon_WebSpider',
        'Xenu Link Sleuth',
        'xintellibot',
        'XoviBot',
        'yacy',
        'yacybot',
        'Yahoo Link Preview',
        'Yahoo! Slurp China',
        'Yahoo! Slurp',
        'YahooSeeker',
        'YahooSeeker-Testing',
        'YandexBot',
        'YandexImages',
        'YandexMetrika',
        'yandex',
        'yanga',
        'Yasaklibot',
        'yeti',
        'YioopBot',
        'YodaoBot',
        'yoogliFetchAgent',
        'yoozBot',
        'YoudaoBot',
        'Zao',
        'Zealbot',
        'zspider',
        'ZyBorg',
    ];

    /**
     * All possible HTTP headers that represent the
     * User-Agent string.
     *
     * @var array
     */
    protected static $uaHttpHeaders = [
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
    ];

    /**
     * Class constructor.
     */
    public function __construct(array $headers = null, $userAgent = null)
    {
        $this->setHttpHeaders($headers);
        $this->setUserAgent($userAgent);
    }

    public function setHttpHeaders($httpHeaders = null)
    {
        // use global _SERVER if $httpHeaders aren't defined
        if (!is_array($httpHeaders) || !count($httpHeaders)) {
            $httpHeaders = $_SERVER;
        }
        // clear existing headers
        $this->httpHeaders = [];
        // Only save HTTP headers. In PHP land, that means only _SERVER vars that
        // start with HTTP_.
        foreach ($httpHeaders as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $this->httpHeaders[$key] = $value;
            }
        }
    }

    public function getUaHttpHeaders()
    {
        return self::$uaHttpHeaders;
    }

    public function setUserAgent($userAgent = null)
    {
        if (false === empty($userAgent)) {
            return $this->userAgent = $userAgent;
        } else {
            $this->userAgent = null;
            foreach ($this->getUaHttpHeaders() as $altHeader) {
                if (false === empty($this->httpHeaders[$altHeader])) { // @todo: should use getHttpHeader(), but it would be slow. (Serban)
                    $this->userAgent .= $this->httpHeaders[$altHeader].' ';
                }
            }

            return $this->userAgent = (!empty($this->userAgent) ? trim($this->userAgent) : null);
        }
    }

    public function getRegex()
    {
        return '('.implode('|', self::$crawlers).')';
    }

    public function isCrawler($userAgent = null)
    {
        $agent = is_null($userAgent) ? $this->userAgent : $userAgent;

        $result = preg_match('/'.$this->getRegex().'/i', $agent, $matches);

        if ($matches) {
            $this->matches = $matches;
        }

        return (bool) $result;
    }

    public function getMatches()
    {
        return $this->matches[0];
    }
}
