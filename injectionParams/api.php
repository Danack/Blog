<?php

use AurynConfig\InjectionParams;
use Blog\Route\Routes;

function injectionParams() : InjectionParams
{
    // These classes will only be created once by the injector.
    $shares = [
        \Auryn\Injector::class,
    ];

    // Alias interfaces (or classes) to the actual types that should be used
    // where they are required.
    $aliases = [
        \VarMap\VarMap::class => \VarMap\Psr7InputMapWithVarMap::class,
        Blog\Route\Routes::class => \Blog\Route\ApiRoutes::class,
    ];

    // Delegate the creation of types to callables.
    $delegates = [
//        \Slim\App::class => 'createAppForApi',
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
