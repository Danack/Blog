<?php

namespace BlogTest\View;

use BlogTest\BaseTestCase;
use Mockery\Mock;
use Arya\Response;
use Jig\JigRender;
use Jig\JigConfig;
use Blog\Content\BlogPost;
use Blog\Data\TemplateList;


class ViewsTest extends BaseTestCase {

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
    }

    function testIndex()
    {
        $jigRender = $this->injector->make('Jig\Jig');
        $className = $jigRender->getTemplateCompiledClassname('pages/index');
        $jigRender->checkTemplateCompiled('pages/index');

        $html = $this->injector->execute([$className, 'render']);
    }

    public function listTemplatesProvider()
    {
        $srcPath = __DIR__."/../../../templates/";
        $srcPath = realpath($srcPath);

        $objects = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($srcPath),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        $templateObjects = new \RegexIterator($objects, '#.*\.tpl#');

        $templates = [];
        foreach ($templateObjects as $key => $var) {
            $templateName = str_replace(
                [".tpl", $srcPath.'/'],
                '',
                $var->getRealPath()
            );
            $templates[] = [$templateName];
        }

        return $templates;
    }
    
    
    /**
     * @group view
     * @param $templateName
     * @dataProvider listTemplatesProvider
     * @throws \Auryn\InjectorException
     */

    function testEachPage($templateName)
    {
        $injector = clone $this->injector;

        $srcPath = __DIR__."/../../../templates/";
        $templates = getTemplates($srcPath);
        $templateList = new TemplateList($templates);
        $injector->share($templateList);

        $blogPost = BlogPost::create(
            getNextBlogPostID(),
            $title = "Hello world",
            $text = "This is a template",
            $datestamp = '2014-05-28 02:06:40'
        );
        
        
        $injector->share($blogPost);
        
        $jigRender = $injector->make('Jig\Jig');
        $jigRender->addDefaultPlugin('Blog\TemplatePlugin\BlogPlugin');

        $className = $jigRender->getTemplateCompiledClassname($templateName);
        $jigRender->checkTemplateCompiled($templateName);
        
        $html = $injector->execute([$className, 'render']);
        $this->assertGreaterThan(0, strlen($html), "Template failed to return any content.");
    }
}




