<?php

declare(strict_types=1);


use Danack\SlimAurynInvoker\SlimAurynInvoker;
use Danack\SlimAurynInvoker\SlimAurynInvokerFactory;
use Danack\Response\StubResponse;
use Danack\Response\StubResponseMapper;
use Psr\Http\Message\ResponseInterface;
use Auryn\Injector;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Stream;
use Zend\Escaper\Escaper;
use Danack\SlimAurynInvoker\RouteParams as InvokerRouteParams;
use Blog\Route;

/**
 * @param $errorNumber
 * @param $errorMessage
 * @param $errorFile
 * @param $errorLine
 * @return bool
 * @throws Exception
 */
function saneErrorHandler($errorNumber, $errorMessage, $errorFile, $errorLine)
{
    if (error_reporting() === 0) {
        // Error reporting has been silenced
        if ($errorNumber !== E_USER_DEPRECATED) {
            // Check it isn't this value, as this is used by twig, with error suppression. :-/
            return true;
        }
    }
    if ($errorNumber === E_DEPRECATED) {
        return false;
    }
    if ($errorNumber === E_CORE_ERROR || $errorNumber === E_ERROR) {
        // For these two types, PHP is shutting down anyway. Return false
        // to allow shutdown to continue
        return false;
    }
    $message = "Error: [$errorNumber] $errorMessage in file $errorFile on line $errorLine.";
    throw new \Exception($message);
}




function getSlimErrorHandler(\Auryn\Injector $injector)
{
    // $logger = $injector->make(\Aitekz\TeamNotifications\TeamNotifier::class);

    return function ($c)/* use ( $logger )*/ {
        return function ($request, $response, $exception) use ($c/*, $logger*/) {

            $text = "";
            /** @var $exception \Exception */
            $currentException = $exception;

            do {
                $text .= get_class($currentException) . ":" . $currentException->getMessage() . "\n\n";
                $text .= $currentException->getTraceAsString();
            } while (($currentException = $currentException->getPrevious()) !== null);

            error_log($text);
            // $logger->alertTeam(NotificationMessage::createFromString($text));

            $response = [
                'status' => 'error',
                'message' => $exception->getMessage(),
                'details' => 'Unhandled exception type ' . get_class($exception),
                'trace' =>  $exception->getTraceAsString()
            ];

            return $c['response']->withStatus(500)
                ->withHeader('Content-Type', 'application/json')
                ->write(\json_encode($response, JSON_PRETTY_PRINT));
        };
    };
}


function setupSlimAurynInvoker(
    Injector $injector,
    ServerRequestInterface $request,
    ResponseInterface $response,
    array $routeArguments
) {
    $injector->alias(ServerRequestInterface::class, get_class($request));
    $injector->share($request);
    $injector->alias(ResponseInterface::class, get_class($response));
    $injector->share($response);
    foreach ($routeArguments as $key => $value) {
        $injector->defineParam($key, $value);
    }

    $invokerRouteParams = new InvokerRouteParams($routeArguments);
    $injector->share($invokerRouteParams);

    $psr7WithRouteParams = \VarMap\Psr7InputMapWithVarMap::createFromRequestAndVarMap(
        $request,
        new \VarMap\ArrayVarMap($routeArguments)
    );
    $injector->share($psr7WithRouteParams);
}


function createApp(\Slim\Container $container, \Auryn\Injector $injector)
{
    $app = new \Slim\App($container);

    $settings = $container->get('settings');
    $settings->replace([
        'determineRouteBeforeAppMiddleware' => true,
    ]);

    $resultMappers = [
        StubResponse::class => [StubResponseMapper::class, 'mapToPsr7Response'],
        Blog\Response::class => 'blogResponseMapper',
        ResponseInterface::class => function (
            ResponseInterface $controllerResult,
            ResponseInterface $originalResponse
        ) {
            return $controllerResult;
        }
    ];

    $exceptionHandlers = [
//        \Danack\Params\ValidationException::class => 'validationExceptionMapper',
//        \PDOException::class => 'pdoExceptionMapper',
//        \Aitekz\Exception\NoValidProfileIdInRouteParams::class => 'aitekzInvalidProfileMapper',
//        \Aitekz\Exception\DuplicateContentException::class => 'aitekzDuplicateContentMapper'
    ];

    $container['foundHandler'] = new SlimAurynInvokerFactory(
        $injector,
        $resultMappers,
        'setupSlimAurynInvoker',
        $exceptionHandlers
    );

    // TODO - this shouldn't be used in production.
    // TODO - convert to JSON response
    $container['notFoundHandler'] = function ($c) {
        return function ($request, $response) use ($c) {
            $response = [
                'status' => 'notfound',
            ];

            return $c['response']->withStatus(404)
                ->withHeader('Content-Type', 'application/json')
                ->write(\json_encode($response, JSON_PRETTY_PRINT));
        };
    };

    // TODO - this shouldn't be used in production.
    // TODO - convert to JSON response
    $container['errorHandler'] = getSlimErrorHandler($injector);

//    $app->add($injector->make(\Aitekz\Middleware\AllowAllCors::class));
    $container['phpErrorHandler'] = function ($container) {
        return $container['errorHandler'];
    };

//    $app->add($injector->make(\Aitekz\Middleware\CacheControlMiddleware::class));
//    $app->add($injector->make(\Aitekz\Middleware\RateLimitMiddleware::class));
//    $app->add($injector->make(\Aitekz\Middleware\ApiRestrictAccessMiddleware::class));

    $routes = $injector->make(\Blog\Route\Routes::class);
    $container['router'] = new \Blog\BlogRouter($routes, $injector, $container);

    return $app;
}



function blogResponseMapper(\Blog\Response $builtResponse, ResponseInterface $response)
{
    $response = $response->withStatus($builtResponse->getStatus());
    foreach ($builtResponse->getHeaders() as $key => $value) {
        /** @var $response \Psr\Http\Message\ResponseInterface */
        $response = $response->withAddedHeader($key, $value);
    }
    $response->getBody()->write($builtResponse->getBody());

    return $response;
}

function showException(\Exception $exception)
{
    echo "oops";
    do {
        echo get_class($exception) . ":" . $exception->getMessage() . "\n\n";
        echo nl2br($exception->getTraceAsString());

        echo "<br/><br/>";
    } while (($exception = $exception->getPrevious()) !== null);
}


function renderBlogPostList(
    Blog\Service\BlogList $blogList,
    \Blog\Model\ActiveBlogPost $activeBlogPost
) {
    foreach ($blogList->getBlogs() as $blogPost) {
        $class = '';
        if ($blogPost->blogPostID == $activeBlogPost->blogPost->blogPostID) {
            $class = 'active';
        }

        $html = <<< HTML
<li class='%s'>
  <a href='%s'>
    %s
  </a>
</li>
HTML;


        printf(
            $html,
            $class,
            Route::blogPost($blogPost),
            $blogPost->getTitle()
        );
    }
}


//function renderBlogList(Blog\Service\BlogList $blogList)
//{
//    foreach ($blogList->getBlogs() as $blogPost) {
//        sprintf(
//            "<li>%s</li>",
//            $blogPost->getTitle()
//        );
//    }
//}



//public function renderTitle()
//{
//    $url = Route::blogPost($this->blogPost);
//
////        Escaper::
////        escapeHtml
//
//    return sprintf(
//        "<a href='%s'>%s</a>",
//        $this->escaper->escapeHtmlAttr($url),
//        $this->escaper->escapeHtml($this->blogPost->title)
//    );
//}

//public function renderDate($includeYear = false)
//{
//    $dateFormat = 'jS M';
//    if ($includeYear == true) {
//        $dateFormat = $dateFormat." Y";
//    }
//
//    return App::formatDateString($this->blogPost->datestamp, $dateFormat);
//}
//






function renderActiveBlogPostTitle(Blog\Model\ActiveBlogPost $activeBlogPost)
{
    printf(
        "<title >%s</title >",
       $activeBlogPost->blogPost->getTitle()
    );
}




function articleImage($imageFilename, $size, $float = 'left', $description = false)
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


function syntaxHighlighter($code)
{
    $text = "<div><pre>";
    $text .= \Blog\Site\CodeHighlighter::highlight($code, 'php');
    $text .= "</pre></div>";

    return $text;
}


/**
 * Decode JSON with actual error detection
 *
 * @param mixed $json
 * @return mixed
 * @throws \BaseReality\Exception\JsonException
 * @throws \Seld\JsonLint\ParsingException
 */
function json_decode_safe($json)
{
    if ($json === null) {
        throw new \BaseReality\Exception\JsonException("Error decoding JSON: cannot decode null.");
    }

    $data = json_decode($json, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        return $data;
    }

    $parser = new \Seld\JsonLint\JsonParser();
    $parsingException = $parser->lint($json);

    if ($parsingException !== null) {
        throw $parsingException;
    }

    if ($data === null) {
        throw new \BaseReality\Exception\JsonException("Error decoding JSON: null returned.");
    }

    throw new \BaseReality\Exception\JsonException("Error decoding JSON: " . json_last_error_msg());
}


function getPostJsonData()
{
    $json = file_get_contents('php://input');
    $data = json_decode_safe($json);

    return $data;
}

function renderBlogPostPreview(
//   \Blog\BlogPostTwig $blogPostTwig,
    \Blog\Content\BlogPost $blogPost
) {
    $finalHtml = $blogPost->getText();

//    $finalHtml = $blogPostTwig->renderBlogPost($blogPost);

    $endPreviewPosition = strpos($finalHtml, "<!-- end_preview -->");
    if ($endPreviewPosition !== false) {
        return substr($finalHtml, 0, $endPreviewPosition);
    }

    return substr($finalHtml, 0, 200);
}

function getExceptionText(\Throwable $exception): string
{
    $text = "";
    do {
        $text .= get_class($exception) . ":" . $exception->getMessage() . "\n\n";
        $text .= $exception->getTraceAsString();

        $exception = $exception->getPrevious();
    } while ($exception !== null);

    return $text;
}

function showTotalErrorPage(\Throwable $exception)
{
    $exceptionText = null;

    $exceptionText = "Failed to get exception text.";

    try {
        $exceptionText = getExceptionText($exception);

        \error_log("Exception in code and Slim error handler failed also: " . get_class($exception) . " " . $exceptionText);
    }
    catch (\Throwable $exception) {
        // Does nothing.
    }

    http_response_code(503);

    if ($exceptionText !== null) {
        var_dump(get_class($exception));
        echo nl2br($exceptionText);
    }
}


/**
 * Creates the objects that map StubResponse into PSR7 responses
 * @return mixed
 */
function getResultMappers(\Auryn\Injector $injector)
{
    return [
        \SlimAuryn\Response\StubResponse::class =>
            'SlimAuryn\mapStubResponseToPsr7',
//        \Bristolian\Page::class => 'mapBristolianPageToPsr7',

        ResponseInterface::class =>
            'SlimAuryn\passThroughResponse',

        'string' =>
            'Blog\StringToHtmlPageConverter::convertStringToHtmlResponse',
    ];
}


function memory_debug()
{
    $memory_used = memory_get_usage(true);
    return "<!-- " . number_format($memory_used) . " -->";
}


/**
 * @param $lang
 * @param $file
 * @return string[]
 */
function syntax_highlighter_file(string $lang, string $file)
{
    $filepath = __DIR__ . "/../files/" . $file;

    if (realpath($filepath) === false) {
        return ["File $file is not available in the files directory.\n"];
    }

    $contents = file_get_contents($filepath);

    if ($contents === false) {
        return ["Failed to read file $file \n"];
    }

    $lines = [];

    $lines []= "<div><pre>";
    $lines []= $contents;//\Blog\Site\CodeHighlighter::highlight($contents, 'php');
    $lines []= "</pre></div>";

    return $lines; //["Ho ho ho $lang, $file \n", $contents];
}


function replace_special_markdown(string $line)
{
    $callbacks = [
        "#{syntaxHighlighterFile lang='(?P<lang>.+)' file='(?P<file>.+)'}#iu" => 'syntax_highlighter_file',
    ];

    foreach ($callbacks as $pattern => $callback) {
        $result = preg_match($pattern, $line, $matches);
        if ($result === 0) {
            continue;
        }

        $params = [];
        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $params[':' . $key] = $value;
            }
        }

        $injector = new Injector();
        // echo "need to call $callback with " . var_export($params, true) . "\n";
        return $injector->execute($callback, $params);
    }

    return [$line];
}
