<?php


use Auryn\InjectorException;
use Auryn\InjectionException;
use Jig\JigException;
use Tier\Tier;
use Tier\TierApp;

use Tier\ResponseBody\ExceptionHtmlBody;
use Room11\HTTP\Response;
use Room11\HTTP\Body;
use Room11\HTTP\Request\Request;

$autoloader = require_once realpath(__DIR__).'/../vendor/autoload.php';

// Contains helper functions for the 'framework'.
require __DIR__ . "/../vendor/danack/tier/src/Tier/tierFunctions.php";

// We need to add the path Jig templates are compiled into to 
// allow them to be autoloaded
$autoloader->add('Jig', [realpath(__DIR__).'/../var/compile/']);
$autoloader->add('Blog', [realpath(__DIR__).'/../var/compile/']);


//require_once "../../clavis.php";
// Contains helper functions for the application.
require_once "appFunctions.php";

// Read application config params
$injectionParams = require_once "injectionParams.php";

register_shutdown_function('Tier\tierShutdownFunction');
set_exception_handler('Tier\tierExceptionHandler');
set_error_handler('Tier\tierErrorHandler');


\Intahwebz\Functions::load();


try {
    $_input = empty($_SERVER['CONTENT-LENGTH']) ? null : fopen('php://input', 'r');
    $request = new Request($_SERVER, $_GET, $_POST, $_FILES, $_COOKIE, $_input);
}
catch (\Exception $e) {
    //TODO - exit quickly.
    header("We totally failed", true, 501);
    echo "we ded ".$e->getMessage();
    exit(0);
}


try {
    // Create the first Tier that needs to be run.
    $tier = new Tier('routeRequest');

    // Create the Tier application
    $app = new TierApp($tier, $injectionParams);

    // Run it
    $app->execute($request);
}
catch (InjectionException $ie) {
    // TODO - add custom notifications.
    
    
    $body = $ie->getMessage();
    $body .= implode("<br/>", $ie->getDependencyChain());

    $body = new ExceptionHtmlBody($body);
    \Tier\sendErrorResponse($request, $body, 500);
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
