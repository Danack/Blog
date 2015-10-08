<?php

use Tier\Tier;
use Tier\TierApp;

use Jig\JigException;

require_once realpath(__DIR__).'/../vendor/autoload.php';

// Contains helper functions for the 'framework'.
require __DIR__ . "/../vendor/danack/tier/src/Tier/tierFunctions.php";

// Contains helper functions for the application.
require_once "appFunctions.php";

\Tier\setupErrorHandlers();

\Intahwebz\Functions::load();

// Read application config params
$injectionParams = require_once "injectionParams.php";

$request = \Tier\createRequestFromGlobals();

// Create the first Tier that needs to be run.
$tier = new Tier('routeRequest');

// Create the Tier application
$app = new TierApp($tier, $injectionParams);
$app->setStandardExceptionHandlers();

// Run it
$app->execute($request);
