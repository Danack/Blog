<?php

use Tier\InjectionParams;

$autoloader = require(__DIR__.'/../../../vendor/autoload.php');

/**
 * @return \Auryn\Injector
 * @throws \Auryn\ConfigException
 */
function createTestInjector($mocks = array(), $aliases = array())
{
    $injector = new \Auryn\Injector();
    //In a larger test suite, InjectionParams would be loaded from a config file
    $injectionParams = new InjectionParams();
    $injectionParams->mergeMocks($mocks);
    foreach ($aliases as $key => $value) {
        $injectionParams->alias($key, $value);
    }

    $injectionParams->addToInjector($injector);
    $injector->share($injector);
    return $injector;
}
