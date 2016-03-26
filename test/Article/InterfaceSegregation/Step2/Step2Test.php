<?php

namespace Article\InterfaceSegregation\Step2;

use Room11\HTTP\Request\CLIRequest;

class Step2Test extends \PHPUnit_Framework_TestCase
{
    function testSearchControllerWorks()
    {
        $varMap = new ArrayVariableMap(['searchTerms' => 'foo,bar']);
        $injector = createTestInjector(
            [VariableMap::class => $varMap],
            [DataSource::class => EchoDataSource::class]
        );
        $result = $injector->execute([SearchController::class, 'search']);
        $this->assertEquals(['foo', 'bar'], $result);
    }
    
    function testSearchControllerException()
    {
        $varMap = new ArrayVariableMap([]);
        $injector = createTestInjector(
            [VariableMap::class => $varMap],
            [DataSource::class => EchoDataSource::class]
        );
        $this->setExpectedException(ParamMissingException::class);
        $injector->execute([SearchController::class, 'search']);
    }

    function testArrayVariableMapWorks()
    {
        $key = 'foo';
        $value = 'bar';
        $varMap = new ArrayVariableMap([$key => $value, 'someother' => 'value']);
        $fooValue = $varMap->getVariable('foo');
        $this->assertEquals($value, $fooValue);
    }

    function testArrayVariableMapException()
    {
        $varMap = new ArrayVariableMap(['zot' => 'fot']);
        $this->setExpectedException(ParamMissingException::class);
        $varMap->getVariable('foo');
    }
    
    function testPSR7VariableMapWorks()
    {
        $cliRequest = new CLIRequest("/?foo=bar", "example.com");
        $varMap = new PSR7VariableMap($cliRequest);
        $fooValue = $varMap->getVariable('foo');
        $this->assertEquals($fooValue, $fooValue);
    }

    function testPSR7VariableMapException()
    {
        $cliRequest = new CLIRequest("/?zot=fot", "example.com");
        $varMap = new PSR7VariableMap($cliRequest);
        $this->setExpectedException(ParamMissingException::class);
        $varMap->getVariable('foo');
    }
}
