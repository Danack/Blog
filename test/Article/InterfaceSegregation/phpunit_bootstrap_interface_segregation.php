<?php


use Tier\InjectionParams;

$autoloader = require(__DIR__.'/../../../vendor/autoload.php');

//
//
//$autoloader->add('BlogTest', [__DIR__]);
//$autoloader->add('BlogMock', [__DIR__]);
//$autoloader->add('BlogStub', [__DIR__]);
//$autoloader->add(
//    "Jig\\PHPCompiledTemplate",
//    [realpath(realpath('./').'/tmp/generatedTemplates/')]
//);
//$autoloader->add(
//    "Jig\\PHPCompiledTemplate",
//    [realpath(realpath('./').'/test/compile/')]
//);


/**
 * @return \Auryn\Injector
 * @throws \Auryn\ConfigException
 */
function createTestInjector($mocks = array(), $aliases = array())
{
    $injector = new \Auryn\Injector();

    // Read application config params
//    $injectionParams = require __DIR__."/./testInjectionParams.php";
//    if (is_object($injectionParams) == false) {
//        var_dump($injectionParams);
//        exit(0);
//    }

    $injectionParams = new InjectionParams();
    $injectionParams->mergeMocks($mocks);
    foreach ($aliases as $key => $value) {
        $injectionParams->alias($key, $value);
    }

    $injectionParams->addToInjector($injector);
    $injector->share($injector);
    return $injector;
}
