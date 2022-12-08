<?php

declare(strict_types=1);

use Auryn\Injector;

use Psr\Http\Message\ResponseInterface;
use SlimAuryn\AurynCallableResolver;
use Laminas\Diactoros\ResponseFactory;
//use Bristolian\Middleware\ExceptionToErrorPageResponseMiddleware;
//use Bristolian\SiteHtml\PageResponseGenerator;

use Blog\Middleware\ExceptionToErrorPageResponseMiddleware;
//use Blog\SiteHtml\PageResponseGenerator;
use Twig\TwigFunction;

function addBlogFunctionsToTwig(\Twig\Environment $twig, \Auryn\Injector $injector)
{
    // Inject function - allows DI in templates.
    $function = new TwigFunction('inject', function (string $type) use ($injector) {
        return $injector->make($type);
    });
    $twig->addFunction($function);

    $function = new TwigFunction('syntaxHighlighterFile', function (string $srcFile, $language) use ($injector) {
        $sourceFileFetcher = $injector->make(\Blog\Service\SourceFileFetcher::class);
        try {
            $contents = $sourceFileFetcher->fetch($srcFile);
            // use $language?
        }
        catch (\Blog\Repository\SourceFileNotFoundException $sfnfe) {
            $contents = "Oops can't find source for: ".$srcFile;
        }

        return $contents;
    });
    $twig->addFunction($function);


    $rawParams = ['is_safe' => array('html')];

    $function = new TwigFunction('memory_debug', function () {
        $memoryUsed = memory_get_usage(true);
        return "<!-- " . number_format($memoryUsed) . " -->";
    }, $rawParams);
    $twig->addFunction($function);


    $function = new TwigFunction('markdown', function ($text) {
        return Michelf\Markdown::defaultTransform($text);
    }, $rawParams);
    $twig->addFunction($function);

    $function = new TwigFunction('syntaxHighlighter', function ($text, $language) {
        return syntaxHighlighter($text, $language);
    }, $rawParams);
    $twig->addFunction($function);

//    articleImage($imageFilename, $size, $float = 'left', $description = false)

    $function = new TwigFunction('articleImage', 'articleImage', $rawParams);
    $twig->addFunction($function);


    $injectorFunctions = [
        'renderBlogPostList',
        'renderBlogList',
        'renderBlogPostListFrontPage',
        'renderActiveBlogPostTitle',
        'renderActiveBlogPostBody',
        'renderSocialData'
    ];

    foreach ($injectorFunctions as $fnName) {
        $function = new TwigFunction($fnName, function () use ($injector, $fnName) {
            return $injector->execute($fnName);
        });
        $twig->addFunction($function);
    }
}


/**
 * @param \Auryn\Injector $injector
 * @param null $language
 * @return Twig\Environment
 */
function createTwigForSite(\Auryn\Injector $injector)
{
    // The templates are included in order of priority.
    $templatePaths = [
        __DIR__ . '/../templates',
        __DIR__ . '/../templates/pages'
    ];

    $loader = new Twig\Loader\FilesystemLoader($templatePaths);
    $twig = new \Twig\Environment(
        $loader,
        array(
            'cache' => false,
            'strict_variables' => true,
            'debug' => true  // TODO - allow configuring
        )
    );


    addBlogFunctionsToTwig($twig, $injector);

//    $function = new Twig_SimpleFunction('requestNonce', function () use ($injector) {
//        $requestNonce = $injector->make(Aitekz\Service\RequestNonce::class);
//
//        return $requestNonce->getRandom();
//    }, $rawParams);
//    $twig->addFunction($function);

    return $twig;
}


/**
 * @param Injector $injector
 * @param \Blog\AppErrorHandler\AppErrorHandler $appErrorHandler
 * @return \Slim\App
 * @throws \Auryn\InjectionException
 */
function createSlimAppForApp(
    Injector $injector,
    \Blog\AppErrorHandler\AppErrorHandler $appErrorHandler
): \Slim\App {

    $callableResolver = new AurynCallableResolver(
        $injector,
        $resultMappers = getResultMappers($injector)
    );

    $app = new \Slim\App(
    /* ResponseFactoryInterface */ $responseFactory = new ResponseFactory(),
        /* ?ContainerInterface */ $container = null,
        /* ?CallableResolverInterface */ $callableResolver,
        /* ?RouteCollectorInterface */ $routeCollector = null,
        /* ?RouteResolverInterface */ $routeResolver = null,
        /* ?MiddlewareDispatcherInterface */ $middlewareDispatcher = null
    );

//    $app->add($injector->make(\Bristolian\Middleware\ExceptionToErrorPageResponseMiddleware::class));
//    $app->add($injector->make(\Bristolian\Middleware\ContentSecurityPolicyMiddleware::class));
////    $app->add($injector->make(\Bristolian\Middleware\BadHeaderMiddleware::class));
////    $app->add($injector->make(\Bristolian\Middleware\AllowedAccessMiddleware::class));
//    $app->add($injector->make(\Bristolian\Middleware\MemoryCheckMiddleware::class));

    return $app;
}


function createMemoryWarningCheck(
//    Config $config,
    Injector $injector
) : \Blog\Service\MemoryWarningCheck\MemoryWarningCheck {

//    if ($config->isProductionEnv()) {
//        return $injector->make(\Blog\Service\MemoryWarningCheck\ProdMemoryWarningCheck::class);
//    }

    return $injector->make(\Blog\Service\MemoryWarningCheck\DevEnvironmentMemoryWarning::class);
}


///**
// * Creates the ExceptionMiddleware that converts all known app exceptions
// * to nicely formatted pages for the app/user facing sites
// */
//function createExceptionToErrorPageResponseMiddleware(Injector $injector): ExceptionToErrorPageResponseMiddleware
//{
//    // TODO - the key is un-needed. Matching the exception handler to the
//    // type of exception could be done via reflection.
//    $exceptionHandlers = [
////        \Blog\Exception\DebuggingCaughtException::class => 'renderDebuggingCaughtExceptionToHtml',
//        \Auryn\InjectionException::class => 'renderAurynInjectionExceptionToHtml',
////        \Bristolian\MarkdownRenderer\MarkdownRendererException::class => 'renderMarkdownRendererException',
//        \ParseError::class => 'renderParseErrorToHtml',
//
//
//        \Throwable::class => 'genericExceptionHandler' // should be last
//    ];
//
//    return new ExceptionToErrorPageResponseMiddleware(
//        $injector->make(PageResponseGenerator::class),
//        $exceptionHandlers
//    );
//}


function createHtmlAppErrorHandler(
//    Config $config,
    \Auryn\Injector $injector
) : \Blog\AppErrorHandler\AppErrorHandler {
//    if ($config->isProductionEnv() === true) {
//        return $injector->make(\Bristolian\AppErrorHandler\HtmlErrorHandlerForProd::class);
//    }

    return $injector->make(\Blog\AppErrorHandler\HtmlErrorHandlerForLocalDev::class);
}
