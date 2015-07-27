<?php

namespace Blog\Controller;

use Tier\ResponseBody\HtmlBody;
use Blog\Data\TemplateList;
use Arya\Request;
use Auryn\Injector;

class TemplateViewer
{

    public function index()
    {
//        $srcPath = __DIR__."/../../../templates/";
//        $templates = getTemplates($srcPath);
//        $templateList = new TemplateList($templates);
        
        return getRenderTemplateTier('pages/templateTest');
    }

    public function displayTemplate(Injector $injector, Request $request)
    {
        $templateInjector = clone $injector;
        
        require_once __DIR__."/../../../test/mockFunctions.php";
        
        $injectionParams = require __DIR__."/../../../test/injectionParams.php";
        
        $jigRender = $injector->make('Jig\Jig');
        $jigRender->addDefaultPlugin('Blog\TemplatePlugin\BlogPlugin');
        
        \Tier\addInjectionParams($injector, $injectionParams);

        $templateName = $request->getFormField('template');
        $displayAsPre = $request->getFormField('displayAsPre');
        
        
        $className = $jigRender->getTemplateCompiledClassname($templateName);
        $jigRender->checkTemplateCompiled($templateName);
        
        $html = $injector->execute([$className, 'render']);

        if ($displayAsPre) {
            $srcPath = __DIR__."/../../../templates/";
            $templates = getTemplates($srcPath);
            $templateList = new TemplateList($templates);

            return getRenderTemplateTier(
                'pages/templateViewer',
                [
                    'Blog\Model\TemplateHTML' => new \Blog\Model\TemplateHTML($html),
                    'Blog\Data\TemplateList' => $templateList
                ]
            );
        }
        
        
        
        return new HtmlBody($html);
    }
}
