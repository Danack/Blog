<?php

use AurynConfig\InjectionParams;

function injectionParams()
{
    // These classes will only be created once by the injector.
    $shares = [
//        \SlimSession\Helper::class,
//        \Twig_Environment::class,
//        \Auryn\Injector::class,
//        \Doctrine\ORM\EntityManager::class,
//        \Birke\Rememberme\Authenticator::class,
//        \Aitekz\TwigRender::class,
//        \PDO::class
    ];


    // Alias interfaces (or classes) to the actual types that should be used
    // where they are required.
    $aliases = [
//        // Aitekz\VariableMap::class => Aitekz\VariableMap\Psr7VariableMap::class,
//        VarMap\VarMap::class => \VarMap\Psr7InputMapWithVarMap::class,
        Blog\Route\Routes::class => \Blog\Route\BlogRoutes::class,
        Blog\Repository\BlogPostRepo::class =>\Blog\Repository\BlogPostRepo\FileBlogPostRepo::class
    ];

    //if (mocking) {
    // Aitekz\Repo\LeadGenRepo::class => \Aitekz\Repo\LeadGenRepo\MockLeadGenRepoRepo::class,
    //}

    // Delegate the creation of types to callables.
    $delegates = [
//        \Psr\Log\LoggerInterface::class => 'createLogger',
//        PDO::class => 'createPDO',
//        \Aitekz\AitekzPdo::class => 'createAitekzPDO',
//        \Aitekz\StrateviePdo::class => 'createStrateviePDO',
//        \Aitekz\ProcessManagerPdo::class => 'createProcessManagerPdo',
//        \Doctrine\ORM\EntityManager::class => 'createDoctrineEntityManager',
//        \Redis::class => 'createRedis',
//        \Elasticsearch\Client::class => 'createElasticsearchInstancesFromConfig',
//        \Aitekz\Service\ScredibleRequestSigner::class => 'createScredibleRequestSigner',
//        \Aitekz\ApiController\ScredibleProxy::class => 'createScredibleProxy',
//        \GuzzleHttp\Client::class => 'createGuzzleHttpClient',
//        \Aitekz\ScredibleAPI::class => 'createScredibleApiFromConfig',
//        \Aitekz\Repo\RateLimitRepo::class => 'createRateLimitRepo',
//        //\Frlnc\Slack\Core\Commander::class => 'createSlackCommanderFromConfig',
//        \Aitekz\TeamNotifications\TeamNotifier::class => 'createTeamLogger',
        \Twig_Environment::class => 'createTwigForSite'
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