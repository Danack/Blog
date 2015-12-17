<?php


use Tier\TierHTTPApp;
use Tier\Executable;
use Composer\Autoload\ClassLoader;
use Room11\HTTP\Request\CLIRequest;



// App keys
require __DIR__."/../../clavis.php";
// App env
require __DIR__."/../autogen/appEnv.php";

$autoloader = require __DIR__.'/../vendor/autoload.php';

//if (method_exists($autoloader, 'setSearchModes')) {
//    $autoloader->setSearchModes([
//            ClassLoader::SEARCHMODE_OPCACHE,
//            ClassLoader::SEARCHMODE_FILE,
//        ]
//    );
//}

// Contains helper functions for the 'framework'.
require __DIR__ . "/../vendor/danack/tier/src/Tier/tierFunctions.php";

// Contains helper functions for the application.
require "appFunctions.php";


\Tier\setupErrorHandlers();

\Intahwebz\Functions::load();

// Read application config params
$injectionParams = require_once "injectionParams.php";

if (strcasecmp(PHP_SAPI, 'cli') == 0) {
    $request = new CLIRequest('/');
}
else {
    $request = \Tier\createRequestFromGlobals();
}


// Create the first Tier that needs to be run.
$executable = new Executable('routeRequest', null, null, 'Room11\HTTP\Body');

// Create the Tier application
$app = new TierHTTPApp($injectionParams);

// Make the body that is generated be shared by TierApp
$app->addExpectedProduct('Room11\HTTP\Body');

// Check to see if a form has been submitted, and we need to do 
// a POST/GET redirect
$app->addPreCallable(['FCForms\HTTP', 'processFormRedirect']);

$app->addGenerateBodyExecutable($executable);
$app->addBeforeSendCallable('addSessionHeader');
$app->addSendCallable('Tier\sendBodyResponse');


$app->createStandardExceptionResolver();

// Run it
$app->execute($request);
