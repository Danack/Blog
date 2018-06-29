<?php

declare(strict_types=1);


/**
 * @param \Auryn\Injector $injector
 * @param null $language
 * @return Twig_Environment
 */
function createTwigForSite(\Auryn\Injector $injector)
{
    // The templates are included in order of priority.
    $templatePaths = [
        __DIR__ . '/../templates',
        __DIR__ . '/../templates/pages'
    ];

    $loader = new Twig_Loader_Filesystem($templatePaths);
    $twig = new Twig_Environment($loader, array(
        'cache' => false,
        'strict_variables' => true,
        'debug' => true  // TODO - allow configuring
    ));

    // Inject function - allows DI in templates.
    $function = new Twig_SimpleFunction('inject', function (string $type) use ($injector) {
        return $injector->make($type);
    });
    $twig->addFunction($function);


    $rawParams = ['is_safe' => array('html')];

    $function = new Twig_SimpleFunction('memory_debug', function () {
        $memoryUsed = memory_get_usage(true);
        return "<!-- " . number_format($memoryUsed) . " -->";
    }, $rawParams);
    $twig->addFunction($function);


    $function = new Twig_SimpleFunction('markdown', function ($text) {
        return Michelf\Markdown::defaultTransform($text);
    }, $rawParams);
    $twig->addFunction($function);


    $injectorFunctions = [
        'renderBlogPostList',
        'renderBlogList',
        'renderBlogPostListFrontPage',
        'renderActiveBlogPostTitle',
        'renderActiveBlogPostBody'
    ];

    foreach ($injectorFunctions as $fnName) {
        $function = new Twig_SimpleFunction($fnName, function () use ($injector, $fnName) {
            return $injector->execute($fnName);
        });
        $twig->addFunction($function);
    }

//    $function = new Twig_SimpleFunction('requestNonce', function () use ($injector) {
//        $requestNonce = $injector->make(Aitekz\Service\RequestNonce::class);
//
//        return $requestNonce->getRandom();
//    }, $rawParams);
//    $twig->addFunction($function);

    return $twig;
}
