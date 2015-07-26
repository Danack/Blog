<?php

namespace Intahwebz\CSSGenerator;


use BaseReality\AutogenPath;
use BaseReality\TemplatePath;

use BaseReality\Mapper\CSSVariableMapper;

use BaseReality\Value\JigRenderCSS as JigRender;
use Jig\ViewModel\BasicViewModel;

use BaseReality\Content\BaseRealityConstant;

function convertToColorString($colorVariables){
    $output = array();

    foreach($colorVariables as $colorVariable){
        $name = $colorVariable['CSSVariable.name'];
        $value = $colorVariable['CSSVariable.value'];
        $output[$name] = str_pad($value, 6, '0', STR_PAD_LEFT);
    }

    return $output;
}


class CSSGenerator {

    /** @var \Jig\JigRender */
    private $jigRender;

    private $autogenPath;
    private $templatePath;

    private $cssVariableMapper;

    function __construct(
        JigRender $jigRender,
        AutogenPath $autogenPath,
        TemplatePath $templatePath,
        CSSVariableMapper $cssVariableMapper) {
        $this->jigRender = $jigRender;
        $this->autogenPath = $autogenPath->getPath();
        $this->templatePath = $templatePath->getPath();
        $this->cssVariableMapper  = $cssVariableMapper;
    }

    /**
     * @param array $filesToGenerate
     * @return bool
     */
    function generateSiteCSS(array $filesToGenerate) {

        foreach($filesToGenerate as $fileroot) {
            $this->parseCSSForVariables($fileroot.'.tpl.css');
            $this->generateCSS($fileroot);
        }

        return true;
    }

    /**
     * @param $templateFilename
     */
    function parseCSSForVariables($templateFilename) {
        $cssVarExtractor = new CSSVarExtractor();
        $cssVarExtractor->analyzeFile($this->templatePath.$templateFilename);
        $cssVarExtractor->writeInfo($this->autogenPath."/cssVarMap.js");
    }

    /**
     * @param $fileRoot
     * @throws \Jig\JigException
     */
    function generateCSS($fileRoot) {
        $cssView = new BasicViewModel(null);

        $colorVariables = $this->cssVariableMapper->getCSSVariablesForTypeAsArray(BaseRealityConstant::$CSS_VARIABLE_COLOR);
        $colorVariables = convertToColorString($colorVariables);

        foreach($colorVariables as $colorName => $colorValue){
            $cssView->setVariable($colorName, ' #'.$colorValue);
        }

        $sizeVariables = $this->cssVariableMapper->getCSSVariablesForTypeAsArray(BaseRealityConstant::$CSS_VARIABLE_SIZE);

        foreach($sizeVariables as $sizeVariable){
            $sizeName = $sizeVariable['CSSVariable.name'];
            $sizeValue = $sizeVariable['CSSVariable.value'];
            $cssView->setVariable($sizeName, ' '.$sizeValue.'px');
        }

        $templateFilename = $fileRoot;
        $renderedCSS = $this->jigRender->renderTemplateFile($templateFilename, $cssView);

        $cssFile = "/*Auto-generated file, do not edit\r\n";
        $cssFile .= "  as changes will be lost. */\r\n\r\n";
        $cssFile .= $renderedCSS;

        $this->saveGeneratedCSSFile($fileRoot, $cssFile);
    }

    /**
     * @param $fileroot
     * @param $cssContents
     * @throws \Exception
     */
    function saveGeneratedCSSFile($fileroot, $cssContents) {
        $cssFilePath = $this->autogenPath."/".$fileroot.".css";
        ensureDirectoryExists($cssFilePath);
        $handle = fopen($cssFilePath, 'w');
        if($handle == false){
            throw new \InvalidArgumentException("Failed to open file ".realpath($cssFilePath)." for writing.");
        }

        fwrite($handle, $cssContents);
        fclose($handle);
    }
}

