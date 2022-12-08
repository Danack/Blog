<?php

use AurynConfig\InjectionParams;

function injectionParams()
{
    // These classes will only be created once by the injector.
    $shares = [
    ];

    // Alias interfaces (or classes) to the actual types that should be used
    // where they are required.
    $aliases = [
//        VarMap\VarMap::class => \VarMap\Psr7InputMapWithVarMap::class,
        Blog\Service\SourceFileFetcher::class => Blog\Service\SourceFileFetcher\RepoSourceFileFetcher::class,
        Blog\Route\Routes::class => \Blog\Route\BlogRoutes::class,

        \Blog\Repository\SourceFileRepo::class =>
        \Blog\Repository\SourceFileRepo\FileBasedSourceFileRepo::class,

        Psr\Http\Message\ResponseFactoryInterface::class =>
            \Laminas\Diactoros\ResponseFactory::class,

        \Blog\MarkdownRenderer\MarkdownRenderer::class =>
            \Blog\MarkdownRenderer\CommonMarkRenderer::class,
    ];


    // Delegate the creation of types to callables.
    $delegates = [
        \Twig\Environment::class  => 'createTwigForSite',

        \Blog\Repository\BlogPostRepo::class => 'createManualBlogPostRepo',

        \Slim\App::class =>
            'createSlimAppForApp',

        \Blog\Service\MemoryWarningCheck\MemoryWarningCheck::class =>
            'createMemoryWarningCheck',

//        \Blog\Middleware\ExceptionToErrorPageResponseMiddleware::class =>
//            'createExceptionToErrorPageResponseMiddleware',

        \Blog\AppErrorHandler\AppErrorHandler::class =>
            'createHtmlAppErrorHandler',
    ];

    // Define some params that can be injected purely by name.
    $params = [];

    $prepares = [
    ];

    $defines = [];

    $injectionParams = new InjectionParams(
        $shares,
        $aliases,
        $delegates,
        $params,
        $prepares,
        $defines
    );

    return $injectionParams;
}