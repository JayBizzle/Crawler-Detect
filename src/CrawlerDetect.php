<?php namespace Jaybizzle\CrawlerDetect;

class CrawlerDetect {

	protected $userAgent = null;

	protected $httpHeaders = array();

	protected $matches = [];
	
	protected static $crawlers = [
		"googlebot\\/",
		"Googlebot-Mobile",
		"Googlebot-Image",
		"Mediapartners-Google",
		"bingbot",
		"slurp",
		"wget",
		"curl",
		"Commons-HttpClient",
		"Python-urllib",
		"libwww",
		"httpunit",
		"nutch",
		"phpcrawl",
		"msnbot",
		"Adidxbot",
		"blekkobot",
		"teoma",
		"ia_archiver",
		"GingerCrawler",
		"webmon ",
		"httrack",
		"webcrawler",
		"FAST-WebCrawler",
		"FAST Enterprise Crawler",
		"FAST Enteprise Crawler",
		"convera",
		"biglotron",
		"grub\.org",
		"UsineNouvelleCrawler",
		"antibot",
		"netresearchserver",
		"speedy",
		"fluffy",
		"jyxobot",
		"bibnum\.bnf",
		"findlink",
		"exabot",
		"gigabot",
		"msrbot",
		"seekbot",
		"ngbot",
		"panscient",
		"yacybot",
		"AISearchBot",
		"IOI",
		"ips-agent",
		"tagoobot",
		"MJ12bot",
		"dotbot",
		"woriobot",
		"yanga",
		"buzzbot",
		"mlbot",
		"yandex",
		"purebot",
		"Linguee Bot",
		"CyberPatrol",
		"voilabot",
		"baiduspider",
		"citeseerxbot",
		"spbot",
		"twengabot",
		"postrank",
		"turnitinbot",
		"scribdbot",
		"page2rss",
		"sitebot",
		"linkdex",
		"ezooms",
		"Mail\.RU_Bot",
		"discobot",
		"heritrix",
		"findthatfile",
		"europarchive\.org",
		"NerdByNature\.Bot",
		"sistrix crawler",
		"ahrefsbot",
		"Aboundex",
		"domaincrawler",
		"wbsearchbot",
		"summify",
		"ccbot",
		"edisterbot",
		"seznambot",
		"ec2linkfinder",
		"gslfbot",
		"aihitbot",
		"intelium_bot",
		"facebookexternalhit",
		"yeti",
		"RetrevoPageAnalyzer",
		"lb-spider",
		"sogou",
		"lssbot",
		"careerbot",
		"wotbox",
		"wocbot",
		"ichiro",
		"DuckDuckBot",
		"lssrocketcrawler",
		"drupact",
		"webcompanycrawler",
		"acoonbot",
		"openindexspider",
		"gnam gnam spider",
		"web-archive-net\.com\.bot",
		"backlinkcrawler",
		"coccoc",
		"integromedb",
		"content crawler spider",
		"toplistbot",
		"seokicks-robot",
		"it2media-domain-crawler",
		"ip-web-crawler\.com",
		"siteexplorer\.info",
		"elisabot",
		"proximic",
		"changedetection",
		"blexbot",
		"arabot",
		"WeSEE:Search",
		"niki-bot",
		"CrystalSemanticsBot",
		"rogerbot",
		"360Spider",
		"psbot",
		"InterfaxScanBot",
		"Lipperhey SEO Service",
		"CC Metadata Scaper",
		"g00g1e\.net",
		"GrapeshotCrawler",
		"urlappendbot",
		"brainobot",
		"fr-crawler",
		"binlar",
		"SimpleCrawler",
		"Livelapbot",
		"Twitterbot",
		"cXensebot",
		"smtbot",
		"bnf\.fr_bot",
		"A6-Indexer",
		"ADmantX",
		"Facebot",
		"OrangeBot",
		"memorybot",
		"AdvBot",
		"XoviBot",
		"QuerySeekerSpider",
		"iisbot",
		"JOC Web Spider",
		"archive-com",
		"Sosospider",
		"Xenu Link Sleuth",
		"Gluten Free Crawler",
		"dataprovider",
		"emailmarketingrobot",
		"Genieo",
		"Riddler",
		"SEOstats",
		"uMBot",
		"netEstate NE Crawler",
		"Pizilla",
		"crawler4j",
		"GermCrawler",
		"008\\/",
		"ABACHOBot",
		"Accoona-AI-Agent",
		"AddSugarSpiderBot",
		"AnyApexBot",
		"Arachmo",
		"B-l-i-t-z-B-O-T",
		"Baiduspider",
		"BecomeBot",
		"BeslistBot",
		"BillyBobBot",
		"Bimbot",
		"Bingbot",
		"BlitzBOT",
		"boitho\.com-dc",
		"boitho\.com-robot",
		"btbot",
		"CatchBot",
		"Cerberian Drtrs",
		"Charlotte",
		"ConveraCrawler",
		"cosmos",
		"Covario-IDS",
		"DataparkSearch",
		"DiamondBot",
		"Discobot",
		"Dotbot",
		"EARTHCOM\.info",
		"EmeraldShield\.com WebBot",
		"envolk\[ITS\]spider",
		"EsperanzaBot",
		"Exabot",
		"FDSE robot",
		"FindLinks",
		"FurlBot",
		"FyberSpider",
		"g2crawler",
		"Gaisbot",
		"GalaxyBot",
		"genieBot",
		"Gigabot",
		"Girafabot",
		"Googlebot",
		"GurujiBot",
		"HappyFunBot",
		"hl_ftien_spider",
		"Holmes",
		"htdig",
		"iaskspider",
		"iCCrawler",
		"igdeSpyder",
		"IRLbot",
		"IssueCrawler",
		"Jaxified Bot",
		"Jyxobot",
		"KoepaBot",
		"L\.webis",
		"LapozzBot",
		"Larbin",
		"LDSpider",
		"LexxeBot",
		"LinkWalker",
		"lmspider",
		"lwp-trivial",
		"mabontland",
		"magpie-crawler",
		"MLBot",
		"Mnogosearch",
		"mogimogi",
		"MojeekBot",
		"Moreoverbot",
		"Morning Paper",
		"MSRBot",
		"MVAClient",
		"mxbot",
		"NetResearchServer",
		"NetSeer Crawler",
		"NewsGator",
		"NG-Search",
		"nicebot",
		"noxtrumbot",
		"Nusearch Spider",
		"NutchCVS",
		"Nymesis",
		"obot",
		"oegp",
		"omgilibot",
		"OmniExplorer_Bot",
		"OOZBOT",
		"Orbiter",
		"PageBitesHyperBot",
		"Peew",
		"polybot",
		"Pompos",
		"PostPost",
		"Psbot",
		"PycURL",
		"Qseero",
		"Radian6",
		"RAMPyBot",
		"RufusBot",
		"SandCrawler",
		"SBIder",
		"ScoutJet",
		"Scrubby",
		"SearchSight",
		"Seekbot",
		"semanticdiscovery",
		"Sensis Web Crawler",
		"SEOChat::Bot",
		"SeznamBot",
		"Shim-Crawler",
		"ShopWiki",
		"Shoula robot",
		"Sitebot",
		"Snappy",
		"sogou spider",
		"Speedy Spider",
		"Sqworm",
		"StackRambler",
		"suggybot",
		"SurveyBot",
		"SynooBot",
		"Teoma",
		"TerrawizBot",
		"TheSuBot",
		"Thumbnail\.CZ robot",
		"TinEye",
		"truwoGPS",
		"TurnitinBot",
		"TweetedTimes Bot",
		"TwengaBot",
		"updated",
		"Urlfilebot",
		"Vagabondo",
		"VoilaBot",
		"Vortex",
		"voyager\\/",
		"VYU2",
		"webcollage",
		"Websquash\.com",
		"wf84",
		"WoFindeIch Robot",
		"WomlpeFactory",
		"Xaldon_WebSpider",
		"yacy",
		"Yahoo! Slurp",
		"Yahoo! Slurp China",
		"YahooSeeker",
		"YahooSeeker-Testing",
		"YandexBot",
		"YandexImages",
		"YandexMetrika",
		"Yasaklibot",
		"Yeti",
		"YodaoBot",
		"yoogliFetchAgent",
		"YoudaoBot",
		"Zao",
		"Zealbot",
		"zspider",
		"ZyBorg",
		"slider\.com",
		"Yahoo Link Preview"
	];

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
        'HTTP_X_UCBROWSER_DEVICE_UA'
    );

	/**
	 * Class constructor
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
        $this->httpHeaders = array();
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
                    $this->userAgent .= $this->httpHeaders[$altHeader] . " ";
                }
            }
            return $this->userAgent = (!empty($this->userAgent) ? trim($this->userAgent) : null);
        }
    }

    public function getRegex()
    {
    	return "(".implode('|',self::$crawlers).")";
    }

    public function isCrawler($userAgent = null)
    {
    	$agent = is_null($userAgent) ? $this->userAgent : $userAgent;

		$result = preg_match("/".$this->getRegex()."/i", $agent, $matches);

		if($matches) {
			$this->matches = $matches;
		}

    	return (bool) $result;
    }

    public function getMatches()
    {
    	return $this->matches;
    }
}
