<?php

use Amp\Artax\Client as ArtaxClient;
use ArtaxServiceBuilder\ResponseCache;
use Arya\Response;
use Jig\JigConfig;
use Jig\Jig;
use Tier\ResponseBody\HtmlBody;
use Jig\JigBase;
use Tier\Tier;
use Tier\InjectionParams;
use Tier\ResponseBody\FileBody;
use GithubService\GithubArtaxService\GithubService;
use Tier\Data\ErrorInfo;
use Arya\Request;
use Blog\Data\TemplateList;
use Jig\Converter\JigConverter;
use Jig\JigException;
use Blog\Service\SourceFileFetcher;
use Auryn\Injector;
use Intahwebz\Session;
use BaseReality\Security\Role;

define('MYSQL_PORT', 3306);
define('MYSQL_USERNAME', 'intahwebz');
define('MYSQL_PASSWORD', 'pass123');
define('MYSQL_ROOT_PASSWORD', 'pass123');
define('MYSQL_SERVER', null);
define('MYSQL_SOCKET_CONNECTION', '/var/lib/mysql/mysql.sock');


/**
 * Read config settings from environment with a default value.
 * @param $env
 * @param $default
 * @return string
 */
function getEnvWithDefault($env, $default)
{
    $value = getenv($env);
    if ($value === false) {
        return $default;
    }
    return $value;
}

function createUploadedFileFetcher()
{
    return new \Intahwebz\Utils\UploadedFileFetcher($_FILES);
}

function createHTTPRequest()
{
    $request = new \Intahwebz\Routing\HTTPRequest(
        $_SERVER,
        $_GET,
        $_POST,
        $_FILES,
        $_COOKIE
    );

    return $request;
}

/**
 * @return JigConfig
 */
function createJigConfig()
{
    $jigConfig = new JigConfig(
        __DIR__."/../templates/",
        __DIR__."/../var/compile/",
        'tpl',
        getEnvWithDefault('jig.compile', \Jig\Jig::COMPILE_ALWAYS)
    );
    
    return $jigConfig;
}



/**
 * @param ArtaxClient $client
 * @param \Amp\Reactor $reactor
 * @param ResponseCache $cache
 * @return GithubService
 */
function createGithubArtaxService(ArtaxClient $client, \Amp\Reactor $reactor, ResponseCache $cache)
{
    return new GithubService($client, $reactor, $cache, "Danack/Tier");
}


function createScriptInclude()
{
    $packScript = getEnvWithDefault('imagickdemo.packscript', 1);
    if ($packScript) {
        return new Intahwebz\Utils\ScriptIncludePacked();
    }
    else {
        return new Intahwebz\Utils\ScriptIncludeIndividual();
    }
}

/**
 * The callable that routes a request.
 * @param Response $response
 * @return Tier
 */
function routeRequest(Request $request,  Response $response) {

    $dispatcher = FastRoute\simpleDispatcher('routesFunction');
    $httpMethod = $request->getMethod();
    $uri = $request->get('REQUEST_URI_PATH');

    $queryPosition = strpos($uri, '?');
    if ($queryPosition !== false) {
        $uri = substr($uri, 0, $queryPosition);
    }

    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND: {
            $response->setStatus(404);
            return getRenderTemplateTier('error/error404');
        }

        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED: {
            // TODO - this is meant to set a header saying which methods
            // are allowed
            $allowedMethods = $routeInfo[1];
            $response->setStatus(405);
            return getRenderTemplateTier('error/error405');
        }

        case FastRoute\Dispatcher::FOUND: {
            $handler = $routeInfo[1];
            $vars = $routeInfo[2];
            $params = InjectionParams::fromParams($vars);

            return new Tier($handler, $params);
        }

        default: {
            //Not supported
            // TODO - this is meant to set a header saying which methods
            $response->setStatus(404);
            return getRenderTemplateTier('error/error404');
            break;
        }
    }
}

/**
 * @param JigBase $template
 * @return HtmlBody
 * @throws Exception
 * @throws \Jig\JigException
 */
function createHtmlBody(JigBase $template)
{
    $text = $template->render();

    return new HtmlBody($text);
}


/**
 * Helper function to allow template rendering to be easier.
 * @param $templateName
 * @param array $sharedObjects
 * @return Tier
 */
function getRenderTemplateTier($templateName, array $sharedObjects = [])
{
    $fn = function (Jig $jigRender) use ($templateName, $sharedObjects) {
        $className = $jigRender->getTemplateCompiledClassname($templateName);
        $jigRender->checkTemplateCompiled($templateName);

        $alias = [];
        $alias['Jig\JigBase'] = $className;
        $injectionParams = new InjectionParams($sharedObjects, $alias, [], []);

        return new Tier('createHtmlBody', $injectionParams);
    };

    return new Tier($fn);
}


function createSendFileTier($filename, $contentType)
{
    $fn = function (Response $response) use ($filename, $contentType) {
        //$response->addHeader('Content-Type', $contentType);
        $fileBody = new FileBody($filename, $contentType);

        return $fileBody;
    };

    return new Tier($fn); 
}




///**
// * Format a time to an rfc2616 timestamp
// * @param $timestamp
// * @return string
// */
//function getLastModifiedTime($timestamp) {
//    return gmdate('D, d M Y H:i:s', $timestamp). ' UTC';
//}
//
///**
// * Get the rfc2616 timestamp of a file
// * @param $fileNameToServe
// * @return string
// */
//function getFileLastModifiedTime($fileNameToServe) {
//    return getLastModifiedTime(filemtime($fileNameToServe));
//}


function correctUmask($filename) {
    $umask = umask();
    $correctMode = ( 0777 - $umask);

    return chmod($filename, $correctMode);
}
    
function saveTmpFile($tmpName, $destFilename) {
    renameMultiplatform($tmpName, $destFilename);
    correctUmask($destFilename);
    //@unlink($tmpName);
}

function getTemplates($directory)
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


function createTemplateList()
{
    $srcPath = __DIR__."/../templates/";
    $templates = getTemplates($srcPath);

    return new TemplateList($templates);
}



/* Stops passwords from being put into log files.
 * Need to make it generate valid php arrays so make life easier.
 */
function dump_table($var){

    $forbiddenKeys = array(
        //'password',
    );

    if(is_array($var) or is_object($var)){
        foreach($var as $key => $value){
            if(is_array($value) or is_object($value)){
                dump_table($value);
            }
            else{
                if(in_array($key, $forbiddenKeys) == true){
                    $value = '********';
                }
                echo "'$key' => '$value' ";
            }
        }
    }
    else{
        echo "'$var' ";
    }
}


//function getOffsetTime($supportedTimeZone){
//
//    if($supportedTimeZone == false){
//        return false;
//    }
//
//    $dateTime = new DateTime();
//    $serverOffsetTime = $dateTime->getOffset();
//    $dateTimeZone = new DateTimeZone($supportedTimeZone);
//    $dateTime->setTimezone($dateTimeZone);
//    $offsetTime = $dateTime->getOffset();
//
//    return $offsetTime - $serverOffsetTime;
//}

function getVar_DumpOutput($response){

    ob_start();

    dump_table($response);

    $obContents = ob_get_contents();

    ob_end_clean();

    return $obContents;
}





/*
function createLibrato()
{
    return new \ImagickDemo\Config\Librato(
        self::getEnv(self::LIBRATO_KEY),
        self::getEnv(self::LIBRATO_USERNAME),
        self::getEnv(self::LIBRATO_STATSSOURCENAME)
    );
}

function createJigConfig()
{
    $jigConfig = new \Jig\JigConfig(
        "../templates/",
        "../var/compile/",
        'tpl',
        $this->getEnv(self::JIG_COMPILE_CHECK)
    );

    return $jigConfig;
}

*/


//function prepareJig(
//    Jig\Jig $jig, AurynInjector $provider) {
//    $cachePath = $provider->make(CachePath::class);
//    $storage = $provider->make(Storage::class); 
//
//    $jig->bindRenderBlock('markdown', 'markdownEnd');
//    $jig->bindRenderBlock(
//        'spoiler',
//        'spoilerBlockEnd',
//        'spoilerBlockStart'
//    );
//
//    /**
//     * @param JigConverter $jigConverter
//     * @param $segmentText
//     * @throws JigException
//     */
//    $processSyntaxHighlighterStart = function (JigConverter $jigConverter, $segmentText) use ($cachePath, $storage) {
//        processSyntaxHighlighterStartFoo($jigConverter, $segmentText, $cachePath, $storage);
//    };
//
//    $jig->bindCompileBlock(
//        'syntaxHighlighter',
//        $processSyntaxHighlighterStart,
//        'processSyntaxHighlighterEnd'
//    );
//    
//    $request = $provider->make(Intahwebz\Request::class);
//
//    $viewTypeMappings = array(
//        'page' => array(
//            'framework/standardContent' => 'framework/standardContentLayout'
//        ),
//        'panel' => array(
//            'framework/standardContent' => 'framework/standardContentPanel'
//        ),
//    );
//    //TODO - detect panel requests
//    $viewType = $request->getVariable('view', 'page');
//
//    if (array_key_exists($viewType, $viewTypeMappings) == false) {
//        throw new \Exception("viewType [$viewType] has no mapping.");
//    }
//
//    $jig->mapClasses($viewTypeMappings[$viewType]);    
//
//    return $jig;
//}


/**
 * Helper function to bind the route list to FastRoute
 * @param \FastRoute\RouteCollector $r
 */
function routesFunction(FastRoute\RouteCollector $r)
{
    $r->addRoute('GET', "/css/{cssInclude}", ['Blog\Controller\ScriptServer', 'getPackedCSS']);
    $r->addRoute('GET', '/js/{jsInclude}', ['Blog\Controller\ScriptServer', 'getPackedJavascript']);
    $r->addRoute('GET', '/rss', ['Blog\Controller\BlogRSS', 'rssFeed' ]);
    $r->addRoute('GET', '/blog/{blogPostID:\d+}[/{title:[^\./]+}{separator:\.?}{format:\w+}]', ['Blog\Controller\Blog', 'display']);

    $r->addRoute('GET', '/blogedit/{blogPostID:\d+}', ['Blog\Controller\BlogEdit', 'showEdit']);
    $r->addRoute('POST', '/blogedit/{blogPostID:\d+}', ['Blog\Controller\BlogEdit', 'processEdit']);
    
    
    
    $r->addRoute('GET', '/staticFile/{filename:\w+}', ['Blog\Controller\ProxyController', 'staticFile']);
    $r->addRoute('GET', '/staticImage/{filename:[^/]+}[/{size:\w+}]', ['Blog\Controller\ProxyController', 'staticImage']);
    $r->addRoute('GET', '/templateViewer', ['Blog\Controller\TemplateViewer', 'index']);
    
    $r->addRoute('GET', '/login', ['Blog\Controller\Login', 'loginGet']);
    $r->addRoute('POST', '/login', ['Blog\Controller\Login', 'loginPost']);
    $r->addRoute('GET', '/logout', ['Blog\Controller\Login', 'logout']);
    
    $r->addRoute('GET', '/draft/{filename:\w+}', ['Blog\Controller\Blog', 'showDraft']);
    $r->addRoute('GET', '/drafts', ['Blog\Controller\Blog', 'showDrafts']);

    $r->addRoute('GET', '/upload', ['Blog\Controller\BlogUpload', 'showUpload']);
    $r->addRoute('POST', '/upload', ['Blog\Controller\BlogUpload', 'uploadPost']);
    $r->addRoute('GET', '/uploadResult', ['Blog\Controller\BlogUpload', 'uploadResult']);

    $r->addRoute('GET', '/blogreplace/{blogPostID:\d+}', ['Blog\Controller\BlogEdit', 'showReplace']);
    $r->addRoute('POST', '/blogreplace/{blogPostID:\d+}', ['Blog\Controller\BlogEdit', 'processReplace']);
    
    
    $r->addRoute('GET', '/', ['Blog\Controller\Blog', 'index']);
}

function routeIndex()
{
    return "/";
}

function ensureAbsoluteFilename ($filename) {
    $filename = str_replace("..", "", $filename);
    $filename = str_replace("/", "", $filename);
    $filename = str_replace("\\", "", $filename);
    return $filename;
}

/**
 * @param $filename
 * @param string $size
 * @return string
 */
function urlStaticImage($filename, $size = 'original') {
    $imageName = $filename;
    $sizeString = $size;
    return "/staticImage/".$sizeString."/".urlencode($imageName);
}

/**
 * @param $imageFilename
 * @param $size
 * @param string $float
 * @param bool $description
 * @return string
 */
function articleImage($imageFilename, $size, $float = 'left', $description = false) {
    $output = '';
    $marginClass = '';
    if ($float == 'left') {
        $marginClass = 'articleMarginFloatLeft';
    }
    if ($float == 'right') {
        $marginClass = 'articleMarginFloatRight';
    }
    $output .= "<div class='articleImage $marginClass' style='float: $float;'>";
    $thumbnailURL = urlStaticImage($imageFilename, $size);
    $fullImageURL = urlStaticImage($imageFilename);
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


function routeBlogPost($blogPostID)
{
    return sprintf('/blog/%d', $blogPostID);
}

function routeDraft($draftFilename)
{
    return sprintf('/draft/%s', $draftFilename);
}


function routeBlogPostWithFormat($blogPostID, $format)
{
    return sprintf('/blog/%d.%d', $blogPostID, $format);
}

function routeJSInclude($url)
{
    return "/js/".$url;        
}

function routeBlogEdit($blogPostID)
{
    return "/blogedit/".$blogPostID;        
}

function routeBlogReplace($blogPostID)
{
    return "/blogreplace/".$blogPostID;        
}

            

            
            