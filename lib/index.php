<?php

error_reporting(E_ALL);


require_once __DIR__ . '/factories.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/app_routes.php';
require_once __DIR__ . '/site_html.php';

set_error_handler('saneErrorHandler');

$injector = new Auryn\Injector();
$injectionParams = injectionParams();
$injectionParams->addToInjector($injector);
$injector->share($injector);

if (strcasecmp(PHP_SAPI, 'cli') == 0) {
    // We only reach CLI here when we are testing, so hard-coded to test particular
    // route.
    require_once __DIR__ . "/../cli_debug.php";
}

set_error_handler('saneErrorHandler');

$injector = new Auryn\Injector();
$injectionParams = injectionParams();
$injectionParams->addToInjector($injector);
$injector->share($injector);

try {
    $app = $injector->make(\Slim\App::class);

    $routes = getAllRoutes();
    foreach ($routes as $standardRoute) {
        list($path, $method, $callable) = $standardRoute;
        $slimRoute = $app->map([$method], $path, $callable);
    }

    $app->run();
}
catch (\Throwable $exception) {
    showTotalErrorPage($exception);
}