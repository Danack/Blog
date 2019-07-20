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
//        // Aitekz\VariableMap::class => Aitekz\VariableMap\Psr7VariableMap::class,
//        VarMap\VarMap::class => \VarMap\Psr7InputMapWithVarMap::class,
        Blog\Service\SourceFileFetcher::class => Blog\Service\SourceFileFetcher\RepoSourceFileFetcher::class,
        Blog\Route\Routes::class => \Blog\Route\BlogRoutes::class,

        \Blog\Repository\SourceFileRepo::class =>
        \Blog\Repository\SourceFileRepo\FileBasedSourceFileRepo::class,
    ];

    //if (mocking) {
    // Aitekz\Repo\LeadGenRepo::class => \Aitekz\Repo\LeadGenRepo\MockLeadGenRepoRepo::class,
    //}

    // Delegate the creation of types to callables.
    $delegates = [
        \Twig\Environment::class  => 'createTwigForSite',
//        \Twig_Environment::class  => 'createTwigForSite',
        \Blog\Repository\BlogPostRepo::class => 'createManualBlogPostRepo',
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