<?php

use Tier\CLIFunction;
use Tier\TierCLIApp;

ini_set('display_errors', 'on');

$autoloader = require_once realpath(__DIR__).'/../vendor/autoload.php';

CLIFunction::setupErrorHandlers();

require __DIR__."/../autogen/appEnv.php";

ini_set('display_errors', 'off');

$injectionParams = require_once __DIR__."/../src/injectionParams.php";

$injectionParams->alias(
    'Danack\Console\Application',
    'Blog\ConsoleApplication'
);

$tierApp = new TierCLIApp($injectionParams);
$tierApp->addInitialExecutable('Tier\Bridge\ConsoleRouter::routeCommand');
$tierApp->execute();
