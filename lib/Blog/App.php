<?php

namespace Blog;

use Amp\Artax\Client as ArtaxClient;
use ArtaxServiceBuilder\ResponseCache;
use ASM\Session;
use ASM\SessionConfig;
use ASM\SessionManager;
use Blog\Config;
use Blog\Data\TemplateList;
use Blog\Route;
use GithubService\GithubArtaxService\GithubService;
use Intahwebz\DB\StatementFactory;
use Jig\Jig;
use Jig\JigConfig;
use Jig\JigDispatcher;
use Psr\Log\LoggerInterface;
use Room11\HTTP\Response;
use Room11\HTTP\VariableMap;
use Room11\HTTP\HeadersSet;
use Tier\TierApp;
use FastRoute\Dispatcher;

class App
{
    public static function createMySQLiConnection(
        Config $config,
        LoggerInterface $logger,
        StatementFactory $statementWrapperFactory
    ) {
        $host = $config->getKey('MYSQL_SERVER');
        $username = $config->getKey('MYSQL_USERNAME');
        $password = $config->getKey('MYSQL_PASSWORD');
        $port = $config->getKey('MYSQL_PORT');
        $socket = $config->getKey('MYSQL_SOCKET_CONNECTION');

        return new \Intahwebz\DB\MySQLiConnection(
            $logger,
            $statementWrapperFactory,
            $host,
            $username,
            $password,
            $port,
            $socket
        );
    }


    /**
     * @return JigConfig
     */
    function createJigConfig(Config $config,
                             \Jig\JigTemplatePath $jigTemplatePath,
                             \Jig\JigCompilePath $jigCompilePath
    ) {
        $jigConfig = new JigConfig(
            $jigTemplatePath,
            $jigCompilePath,
            $config->getKey(Config::JIG_COMPILE_CHECK),
            'tpl'
        );

        return $jigConfig;
    }

    public static function createCaching()
    {
        return new \Room11\Caching\LastModified\Revalidate(3600, 1200);
    }

    public static function createDispatcher()
    {
        $dispatcher = \FastRoute\simpleDispatcher(['Blog\App', 'routesFunction']);

        return $dispatcher;
    }


    /**
     * @param ArtaxClient $client
     * @param \Amp\Reactor $reactor
     * @param ResponseCache $cache
     * @return GithubService
     */
    public static function createGithubArtaxService(
        ArtaxClient $client,
        \Amp\Reactor $reactor,
        ResponseCache $cache
    ) {
        return new GithubService($client, $reactor, $cache, "Danack/Tier");
    }


    public static function createScriptInclude(
        Config $config,
        \ScriptHelper\ScriptURLGenerator $scriptURLGenerator
    ) {
        $packScript = $config->getKey(Config::SCRIPT_PACKING);

        if ($packScript) {
            return new \ScriptHelper\ScriptInclude\ScriptIncludePacked($scriptURLGenerator);
        }
        else {
            return new \ScriptHelper\ScriptInclude\ScriptIncludeIndividual($scriptURLGenerator);
        }
    }


    public static function correctUmask($filename)
    {
        $umask = umask();
        $correctMode = (0777 - $umask);

        return chmod($filename, $correctMode);
    }

    public static function saveTmpFile($tmpName, $destFilename)
    {
        renameMultiplatform($tmpName, $destFilename);
        self::correctUmask($destFilename);
    }

    public static function getTemplates($directory)
    {
        $srcPath = realpath($directory);

        $objects = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($srcPath),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        $templateObjects = new \RegexIterator($objects, '#.*\.tpl#');

        $templates = [];
        foreach ($templateObjects as $key => $var) {
            $templateName = str_replace(
                [".tpl", $srcPath.'/'],
                '',
                $var->getRealPath()
            );
            $templates[$templateName] = $templateName;
        }

        return $templates;
    }


    public static function createTemplateList()
    {
        $srcPath = __DIR__."/../templates/";
        $templates = self::getTemplates($srcPath);

        return new TemplateList($templates);
    }


    /**
     * Helper function to bind the route list to FastRoute
     * @param \FastRoute\RouteCollector $r
     */
    public static function routesFunction(\FastRoute\RouteCollector $r)
    {
        $r->addRoute('GET', "/react", ['Blog\Controller\ReactTest', 'reactTest']);

        $r->addRoute('GET', "/setup2fa", ['Blog\Controller\Registration', 'setup2fa']);
        $r->addRoute('GET', "/complete2fa", ['Blog\Controller\Registration', 'complete2fa']);

        $r->addRoute('GET', "/reactapi", ['Blog\Controller\ReactTest', 'reactApi']);

        $r->addRoute('POST', "/api/template_render", ['Blog\Controller\ReactTest', 'template_render']);


        $r->addRoute('GET', "/css/{commaSeparatedFilenames}", ['ScriptHelper\Controller\ScriptServer', 'serveCSS']);
        $r->addRoute('GET',
            '/js/{commaSeparatedFilenames}',
            ['ScriptHelper\Controller\ScriptServer', 'serveJavascript']
        );

        $r->addRoute('GET', '/rss', ['Blog\Controller\BlogRSS', 'rssFeed']);
        $r->addRoute(
            'GET',
            '/blog/{blogPostID:\d+}[/{title:[^\./]+}{separator:\.?}{format:\w+}]',
            ['Blog\Controller\Blog', 'display']
        );
        $r->addRoute('GET', '/blogedit/{blogPostID:\d+}', ['Blog\Controller\BlogEdit', 'showEdit']);
        $r->addRoute('POST', '/blogedit/{blogPostID:\d+}', ['Blog\Controller\BlogEdit', 'processEdit']);
        //$r->addRoute('GET', '/staticFile/{filename:\w+}', ['Blog\Controller\ProxyController', 'staticFile']);
        $r->addRoute(
            'GET',
            '/staticImage/{filename:[^/]+}[/{size:\w+}]',
            ['Blog\Controller\ProxyController', 'staticImage']
        );

        $r->addRoute('GET', '/templateViewer', ['Blog\Controller\TemplateViewer', 'index']);
        $r->addRoute('POST', '/templateViewer', ['Blog\Controller\TemplateViewer', 'displayTemplate']);

        $r->addRoute('GET', '/login', ['Blog\Controller\Login', 'loginGet']);
        $r->addRoute('POST', '/login', ['Blog\Controller\Login', 'loginPost']);
        $r->addRoute('GET', '/logout', ['Blog\Controller\Login', 'logout']);

        $r->addRoute('GET', '/draft/{filename:\w+}', ['Blog\Controller\Blog', 'showDraft']);
        $r->addRoute('GET', '/drafts', ['Blog\Controller\Blog', 'showDrafts']);

        $r->addRoute('GET', '/upload', ['Blog\Controller\BlogUpload', 'showUpload']);
        $r->addRoute('POST', '/upload', ['Blog\Controller\BlogUpload', 'uploadPost']);

        $r->addRoute('GET', '/uploadFile', ['Blog\Controller\FileUpload', 'showUpload']);
        //$r->addRoute('POST', '/uploadFile', ['Blog\Controller\FileUpload', 'uploadPost']);

        //$r->addRoute('GET', '/listFiles', ['Blog\Controller\FileUpload', 'listFiles']);


        $r->addRoute('GET', '/uploadResult', ['Blog\Controller\BlogUpload', 'uploadResult']);
        $r->addRoute('GET', '/blogreplace/{blogPostID:\d+}', ['Blog\Controller\BlogEdit', 'showReplace']);
        $r->addRoute('POST', '/blogreplace/{blogPostID:\d+}', ['Blog\Controller\BlogEdit', 'processReplace']);
        //$r->addRoute('GET', '/staticFile/{filename:[^/]+}', ['Blog\Controller\Proxy', 'staticFile']);

        $r->addRoute('GET', '/perfTest', ['Blog\Controller\Blog', 'perfTest']);
        $r->addRoute('GET', '/sourceFile/{filename:.+}', ['Blog\Controller\FileUpload', 'showFile']);
        $r->addRoute('GET', '/listFiles', ['Blog\Controller\FileUpload', 'listFiles']);
        $r->addRoute('GET', '/', ['Blog\Controller\Blog', 'index']);
    }


    public static function ensureAbsoluteFilename($filename)
    {
        $filename = str_replace("..", "", $filename);
        $filename = str_replace("/", "", $filename);
        $filename = str_replace("\\", "", $filename);
        return $filename;
    }


    /**
     * @param $imageFilename
     * @param $size
     * @param string $float
     * @param bool $description
     * @return string
     */
    public static function articleImage($imageFilename, $size, $float = 'left', $description = false)
    {
        $output = '';
        $marginClass = '';
        if ($float == 'left') {
            $marginClass = 'articleMarginFloatLeft';
        }
        if ($float == 'right') {
            $marginClass = 'articleMarginFloatRight';
        }
        $output .= "<div class='articleImage $marginClass' style='float: $float;'>";
        $thumbnailURL = Route::staticImage($imageFilename, $size);
        $fullImageURL = Route::staticImage($imageFilename);
        $output .= "<a href='$fullImageURL' target='_blank' class='plainLink'>";
        $output .= "<img src='$thumbnailURL'/> ";
        //Size could actually just be setting the height - which would be annoying.
        //So we don't support that.
        $width = intval($size);
        if ($description != false) {
            $output .= "<br/>";
            $output .= "<div style='width: ".$width."px'>";
            $output .= $description;
            $output .= "</div>";
        }
        $output .= "</a></div>";

        return $output;
    }

    public static function createASMFileDriver()
    {
        return new \ASM\File\FileDriver(__DIR__."/../../var/session/");
    }


    /**
     * @param \ASM\Redis\RedisDriver $redisDriver
     * @return \ASM\Session
     */
    public static function createSession(\ASM\Driver $driver)
    {
        $sessionConfig = new SessionConfig(
            'SessionTest',
            1000,
            10
        );

        $sessionManager = new SessionManager(
            $sessionConfig,
            $driver
        );

        $session = $sessionManager->createSession($_COOKIE);

        return $session;
    }


    /**
     * @param Session $session
     * @param HeadersSet $headerSet
     */
    public static function addSessionHeader(Session $session, HeadersSet $headerSet)
    {
        $session->save();
        $headers = $session->getHeaders(
            \ASM\SessionManager::CACHE_PRIVATE,
            '/'
        );

        foreach ($headers as $key => $value) {
            $headerSet->addHeader($key, $value);
        }

        return TierApp::PROCESS_CONTINUE;
    }

    public static function createUserPermissions(Session $session)
    {
        $role = $session->getSessionVariable(\Blog\Site\Constant::$userRole);

        if ($role == false) {
            return new \Blog\User\AnonymousPermissions();
        }

        return new \Blog\User\LoggedInPermissions($role);
    }


    public static function prepareJig(Jig $jig, $injector)
    {
        $jig->addDefaultPlugin('Blog\TemplatePlugin\BlogPostPlugin');
    }


    public static function prepareJigDispatcher(JigDispatcher $jigDispatcher, $injector)
    {
        $jigDispatcher->addDefaultPlugin('Blog\TemplatePlugin\BlogPostPlugin');
    }
    
    /**
     * ensureDirectoryExists by creating it with 0755 permissions and throwing
     * an exception if it does not exst after that mkdir call.
     * @param $outputFilename
     * @throws \Exception
     */
    public function ensureDirectoryExists($outputFilename)
    {
        $directoryName = dirname($outputFilename);
        @mkdir($directoryName, 0755, true);
        //TODO - double-check umask
        if (file_exists($directoryName) === false) {
            throw new \Exception("Directory $directoryName does not exist and could not be created");
        }
    }
    
    //Converts an SQL datestamp into a formatted string
// e.g. 2008-11-11 16:20:24
// -> 11th Nov 08
    public static function formatDateString($datestamp, $format, $offsetTime = 0)
    {
        $year = intval(mb_substr($datestamp, 0, 4));
        $month = intval(mb_substr($datestamp, 5, 2));
        $day = intval(mb_substr($datestamp, 8, 2));

        $hours = intval(mb_substr($datestamp, 11, 2));
        $minutes = intval(mb_substr($datestamp, 14, 2));
        $seconds = intval(mb_substr($datestamp, 17, 2));

        $unixTimestamp = mktime($hours, $minutes, $seconds, $month, $day, $year);
        $unixTimestamp += $offsetTime;

        return date($format, $unixTimestamp);
    }

}
