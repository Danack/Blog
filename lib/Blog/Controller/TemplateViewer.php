<?php

namespace Blog\Controller;

use Auryn\Injector;
use Blog\Data\TemplateList;
use Room11\HTTP\VariableMap;
use Room11\HTTP\Body\HtmlBody;
use Tier\TierFunction;
use Tier\Bridge\JigExecutable;

class TemplateViewer
{
    public function index(Injector $injector, VariableMap $request)
    {
        $templateName = $request->getVariable('template');
        $displayAsPre = $request->getVariable('displayAsPre');
        
        if ($templateName == false ||
            $displayAsPre == false) {
            return JigExecutable::create('pages/test/templateTest');
        }

        $srcPath = __DIR__."/../../../templates/";
        $templates = getTemplates($srcPath);
        
        if (!in_array($templateName, $templates)) {
            return JigExecutable::create('pages/test/templateTest');
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

        //$className = $jigRender->getFQCNFromTemplateName($templateName);
        $className = $jigRender->compile($templateName);

        $html = $templateInjector->execute([$className, 'render']);

        if ($displayAsPre) {
            $templateList = new TemplateList($templates);

            return JigExecutable::createWithSharedObjects(
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
