<?php

namespace Blog\Controller;

use Auryn\Injector;
use Blog\Data\TemplateList;
use Room11\HTTP\Request\Request;
use Room11\HTTP\Body\HtmlBody;
use Tier\InjectionParams;

class TemplateViewer
{

    public function index(Injector $injector, Request $request)
    {
//        return \Tier\getRenderTemplateTier('pages/test/templateTest');
//    }
//
//    public function displayTemplate(Injector $injector, Request $request)
//    {
        if ($request->hasQueryParameter('template') == false ||
            $request->hasQueryParameter('displayAsPre') == false) {
            return \Tier\getRenderTemplateTier('pages/test/templateTest');
        }
        
        $templateName = $request->getQueryParameter('template');
        $displayAsPre = $request->getQueryParameter('displayAsPre');

        $srcPath = __DIR__."/../../../templates/";
        $templates = getTemplates($srcPath);
        
        if (!in_array($templateName, $templates)) {
            return \Tier\getRenderTemplateTier('pages/test/templateTest');
        }

        $templateInjector = clone $injector;
 
        require_once __DIR__."/../../../test/mockFunctions.php";
        
        $injectionParams = require __DIR__."/../../../test/testInjectionParams.php";
        
        $jigRender = $templateInjector->make('Jig\Jig');
        $jigRender->addDefaultPlugin('Blog\TemplatePlugin\BlogPlugin');
        
        $templateInjector->share($jigRender);

        mockAllForms($templateInjector);

        /** @var $templateName \Tier\InjectionParams */
        $injectionParams->addToInjector($templateInjector);

        $className = $jigRender->getFQCNFromTemplateName($templateName);
        $jigRender->checkTemplateCompiled($templateName);
        
        $html = $templateInjector->execute([$className, 'render']);

        if ($displayAsPre) {
            
            
            $templateList = new TemplateList($templates);

            return \Tier\getRenderTemplateTier(
                'pages/test/templateViewer',
                [
                    'Blog\Model\TemplateHTML' => new \Blog\Model\TemplateHTML($html),
                    'Blog\Data\TemplateList' => $templateList
                ]
            );
        }

        return new HtmlBody($html);
    }
}
