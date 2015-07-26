<?php

namespace BlogTest\Contorller;

use BlogTest\BaseTestCase;
use Mockery\Mock;
use Arya\Response;

class ScriptServerTest extends BaseTestCase {

    /**
     * @var \Auryn\Injector
     */
    private $injector;
    
    /**
     * 
     */
    function setUp()
    {
        parent::setUp();
        $this->injector = createTestInjector();

        $this->injector->alias('Blog\FilePacker', 'BlogMock\MockFilePacker');
        $this->injector->share('BlogMock\MockFilePacker');
    }
//
//    function testSingleCSS() {
//        
//        $response = new Response();
//        $this->injector->share($response);
//        $scriptServer = $this->injector->make('Blog\Controller\ScriptServer');
//        $scriptServer->getSingleCSS("test");
//        $this->assertTrue($response->hasBody(), 'Response does not have a body set.');
//        $this->assertInstanceOf('Tier\ResponseBody\FileBodyEx', $response->getBody());
//        $this->assertTrue($response->hasHeader('Content-Type'), 'Response content type not set.');
//        $this->assertEquals("text/css", $response->getHeader('Content-Type'));
//    }
//
//   function testSingleJS()
//   {
//       $response = new Response();
//       $this->injector->share($response);
//       $scriptServer = $this->injector->make('Blog\Controller\ScriptServer');
//       $scriptServer->getSingleJavascript("test");
//       $this->assertTrue($response->hasBody(), 'Response does not have a body set.');
//       $this->assertInstanceOf('Tier\ResponseBody\FileBodyEx', $response->getBody());
//       $this->assertTrue($response->hasHeader('Content-Type'), 'Response content type not set.');
//       $this->assertEquals('application/javascript', $response->getHeader('Content-Type'));
//   }
//    
    
    
    
//    function testExtractItems()
//    {
//        $item1 = 'foo';
//        $item2 = 'bar';
//        $cssInclude = "$item1, $item2, 123";
//        $items = \Blog\Controller\extractItems($cssInclude);
//        
//        $this->assertInternalType('array', $items);
//        $this->assertContains($item1, $items);
//        $this->assertContains($item2, $items);
//    }

    function testJSPack()
    {
        $item1 = 'foo';
        $item2 = 'bar';
        $jsInclude = "$item1, $item2, 123";

        $this->injector->defineParam('jsInclude', $jsInclude);
        
        $response = $this->injector->execute(
            ['Blog\Controller\ScriptServer', 'getPackedJavascript']
        );
        
        $this->assertInstanceOf('Arya\FileBody', $response);
    }
}




