<?php

use Composer\Autoload\ClassLoader;
use Configurator\ConfiguratorException;
use Tier\Executable;
use Tier\InjectionParams;
use Tier\TierFunction;
use Tier\TierHTTPApp;
use Room11\HTTP\Request\CLIRequest;
use Blog\Config;
use Tier\HTTPFunction;

ini_set('display_errors', 'on');

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

// Contains helper functions for the application.
//require "appFunctions.php";

HTTPFunction::setupErrorHandlers();

ini_set('display_errors', 'off');

\Intahwebz\Functions::load();

// Read application config params
$injectionParams = require_once "injectionParams.php";

if (strcasecmp(PHP_SAPI, 'cli') == 0) {
    $request = new CLIRequest('/', 'blog.basereality.com');
}
else {
    $request = HTTPFunction::createRequestFromGlobals();
}

// Create the routing Executable that needs to be run.
$routingExecutable = new Executable(
    ['Tier\Bridge\FastRouter', 'routeRequest'],
    null,
    null,
    'Room11\HTTP\Body' //skip if this has already been produced
);


$setupRepoInjection = function (Config $config) {
    $repoParams = [];
    
    $repoParams[Config::REPOSITORY_MAPPING_SQL] = [
        'Blog\Repository\BlogPostRepo' => 'Blog\Repository\SQL\BlogPostSQLRepo',
        'Blog\Repository\LoginRepo' => 'Blog\Repository\SQL\LoginSQLRepo',
        'Blog\Repository\SourceFileRepo' => 'Blog\Repository\SQL\SourceFileSQLRepo',
    ];

    $repoParams[Config::REPOSITORY_MAPPING_STUB] = [
        'Blog\Repository\BlogPostRepo' => 'Blog\Repository\Stub\BlogPostStubRepo',
        'Blog\Repository\LoginRepo' => 'Blog\Repository\\Blog\Mapper\Stub\LoginStubRepo',
        'Blog\Repository\SourceFileRepo' => 'Blog\Repository\Stub\SourceFileStubRepo',
    ];

    $configValue = $config->getKey(Config::REPOSITORY_MAPPING);
    if (array_key_exists($configValue, $repoParams) === false) {
        throw new ConfiguratorException("Unknown config for [".Config::REPOSITORY_MAPPING."] of [$configValue]");
    }

    $aliases = $repoParams[$configValue];
    $injectionParams = new InjectionParams([], $aliases);
    
    return $injectionParams;
};

// Create the Tier application
$app = new TierHTTPApp($injectionParams);

// Make the body that is generated be shared by TierApp
$app->addExpectedProduct('Room11\HTTP\Body');

$app->addInitialExecutable($setupRepoInjection);

$dbTest = function (\Intahwebz\DB\Connection $connection) {
    $statement = $connection->prepareStatement("select wordFrequencyID, word, frequency from basereality.wordFrequency;");
    $result = $statement->execute();
    $result->bind_result($wordFrequencyID, $word, $frequency);
    
    while ($result->fetch()) {
        var_dump($wordFrequencyID, $word, $frequency);
        echo "<br/>";
    }

    echo "harro";
    exit(0);
};

//$app->addInitialExecutable($dbTest);


// Check to see if a form has been submitted, and we need to do 
// a POST/GET redirect
$app->addBeforeGenerateBodyExecutable(['FCForms\HTTP', 'processFormRedirect']);

$app->addGenerateBodyExecutable($routingExecutable);
$app->addBeforeSendExecutable('Blog\App::addSessionHeader');
$app->addSendExecutable(['Tier\HTTPFunction', 'sendBodyResponse']);

$app->createStandardExceptionResolver();

// Run it
$app->execute($request);
