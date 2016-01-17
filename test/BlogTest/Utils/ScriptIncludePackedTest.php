<?php

namespace BlogTest\Contorller;

use BlogTest\BaseTestCase;
use Mockery\Mock;
use Arya\Response;

/**
 * @param $htmlFragment
 * @param $name
 * @return \DOMElement
 * @throws \Exception
 */
function convertFragmentToElement($htmlFragment, $name)
{
    $doc = new \DOMDocument();
    $doc->loadHTML($htmlFragment);
    $elements = $doc->getElementsByTagName("$name");

    foreach ($elements as $item) {
        return $item;
    }

    throw new \Exception("Failed to find tag $name in fragment: ".$htmlFragment);
}


/**
 * @param $htmlFragment
 * @param $name
 * @return \DOMNodeList
 */
function convertFragmentToElements($htmlFragment, $name)
{
    $doc = new \DOMDocument();
    $doc->loadHTML($htmlFragment);
    $elements = $doc->getElementsByTagName("$name");

    return $elements;
}


class ScriptIncludePackedTest extends BaseTestCase
{
    /**
     * @var \Auryn\Injector
     */
    private $injector;
    
    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->injector = createTestInjector();
//
//        $this->injector->alias('Blog\FilePacker', 'BlogMock\MockFilePacker');
//        $this->injector->share('BlogMock\MockFilePacker');
    }


    public function testCSSPack()
    {
        $scriptIncludePacked = $this->injector->make('Intahwebz\Utils\ScriptIncludePacked');
        $scriptIncludePacked->addCSS('foo');
        $scriptIncludePacked->addCSS('bar');

        $result = $scriptIncludePacked->includeCSS();

        $item = convertFragmentToElement($result, "link");

        $this->assertTrue($item->hasAttribute("media"));
        $this->assertEquals('screen', $item->getAttribute("media"));

        $this->assertTrue($item->hasAttribute("href"));
        $this->assertContains('foo', $item->getAttribute("href"));
        $this->assertContains('bar', $item->getAttribute("href"));
    }
    
    public function testIncludeJSRequiredIndividual()
    {
        $scriptIncludePacked = $this->injector->make('Intahwebz\Utils\ScriptIncludeIndividual');
        $scriptIncludePacked->addJSRequired('foo');
        $scriptIncludePacked->addJSRequired('bar');

        $result = $scriptIncludePacked->emitJSRequired();
        
        $scripts = convertFragmentToElements($result, 'script');

        $fooFound = false;
        $barFound = false;
        
        foreach ($scripts as $scriptElement) {
            /** @var $scriptElement \DomElement */
            $src = $scriptElement->getAttribute("src");

            if (strpos($src, 'foo') !== false) {
                $fooFound = true;
            }
            if (strpos($src, 'bar') !== false) {
                $barFound = true;
            }
        }

        $this->assertTrue($fooFound);
        $this->assertTrue($barFound);
    }

    public function testIncludeJSRequiredPacked()
    {
        $scriptIncludePacked = $this->injector->make('Intahwebz\Utils\ScriptIncludePacked');
        $scriptIncludePacked->addJSRequired('foo');
        $scriptIncludePacked->addJSRequired('bar');
        
        $result = $scriptIncludePacked->emitJSRequired();
        
        /*
         
          <script type='text/javascript'>        
var baserealityScripts = [];
        
function setJSLoaded(jsFileName, isLoaded) {
    baserealityScripts[jsFileName] = isLoaded;
}
 
function dumpFailedJS(){
}
</script><script type='text/javascript'>
setJSLoaded('foo.js', false);
setJSLoaded('bar.js', false);
</script>
<script type='text/javascript' src='/js/jsInclude/1.2.3,foo,bar'></script>
        <script type='text/javascript'>
dumpFailedJS();
</script>

        
        */
    }
}
