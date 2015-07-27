<?php

use Arya\Request;
use Auryn\InjectorException;
use Jig\JigException;
use Tier\Tier;
use Tier\TierApp;
use Tier\ResponseBody\ExceptionHtmlBody;

$autoloader = require_once realpath(__DIR__).'/../vendor/autoload.php';

// Contains helper functions for the 'framework'.
require_once "../lib/Tier/tierFunctions.php";

set_error_handler('tierErrorHandler');
register_shutdown_function('tierShutdownFunction');

// We need to add the path Jig templates are compiled into to 
// allow them to be autoloaded
$autoloader->add('Jig', [realpath(__DIR__).'/../var/compile/']);
$autoloader->add('Blog', [realpath(__DIR__).'/../var/compile/']);

// Read application config params
$injectionParams = require_once "injectionParams.php";

require_once "../../clavis.php";
// Contains helper functions for the application.
require_once "appFunctions.php";
require_once "makeCSHappy.php";



\Intahwebz\Functions::load();

try {
    $_input = empty($_SERVER['CONTENT-LENGTH']) ? null : fopen('php://input', 'r');
    $request = new Request($_SERVER, $_GET, $_POST, $_FILES, $_COOKIE, $_input);
}
catch (\Exception $e) {
    //TODO - exit quickly.
    header("We totally failed", true, 501);
    exit(0);
}

try {
    // Create the first Tier that needs to be run.
    $tier = new Tier('routeRequest', $injectionParams);

    // Create the Tier application
    $app = new TierApp($tier);

    // Run it
    $app->execute($request);
}
catch (InjectorException $ie) {
    // TODO - add custom notifications.
    $body = new ExceptionHtmlBody($ie);
    \Tier\sendErrorResponse($request, $body, 500);
}
catch (JigException $je) {
    $body = new ExceptionHtmlBody($je);
    \Tier\sendErrorResponse($request, $body, 500);
}
catch (\Exception $e) {
    $body = new ExceptionHtmlBody($e);
    \Tier\sendErrorResponse($request, $body, 500);
}
