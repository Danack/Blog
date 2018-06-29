<?php

error_reporting(E_ALL);

require_once __DIR__ . "/../vendor/autoload.php";

require_once __DIR__ . '/factories.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/injectionParamsHttp.php';
// require_once __DIR__ . '/functions_test.php';

set_error_handler('saneErrorHandler');

$injector = new Auryn\Injector();
$injectionParams = injectionParams();
$injectionParams->addToInjector($injector);
$injector->share($injector);

$container = new \Slim\Container;


// TODO - this should be moved to just the services that require a session.
//$session_save_handler = getConfig(['', 'session', 'save_handler']);
//if ($session_save_handler !== null) {
//    ini_set('session.save_handler', $session_save_handler);
//    ini_set('session.save_path', getConfig(['session', 'save_path']));
//}

if (strcasecmp(PHP_SAPI, 'cli') == 0) {
    // We only reach CLI here when we are testing, so hard-coded to test particular
    // route.
    require_once __DIR__ . "/../cli_debug.php";
}


try {
    $app = createApp($container, $injector);
    $app->run();
}
catch (\Exception $exception) {
    echo "Exception in code and Slim error handler failed also: <br/>";
    var_dump(get_class($exception));
    showException($exception);
}
