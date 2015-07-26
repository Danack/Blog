<?php


namespace Intahwebz\Utils;

//use BaseReality\Value\PackScripts;
//use BaseReality\Value\UseCDNForScripts;
//use BaseReality\Value\SiteScriptVersion;

class ScriptIncludePacked extends ScriptInclude {

    var $closedJSBlocks = array();

    var $useCDNForScripts = true;

//    private $currentJSBlock = array();

    //private $scriptVersion;
    
    var $includeJSArray = array();

    var $onBodyLoadJavascript = array();

//    private $domain;

    /**
     * @var bool
     */
    private $showJSErrors;

    /**
     * @param $packScripts
     * @param $useCDNForScripts
     * @param \Intahwebz\Domain $domain
     * @param $liveServer
     * @param $siteScriptVersion
     */
    function __construct(
        //\Intahwebz\Domain $domain//,
//        $useCDNForScripts,
//        $packScripts,
//        $showJSErrors,
//        $siteScriptVersion
    ) {
        
        //$this->domain = $domain;
        $this->useCDNForScripts = false;//$useCDNForScripts->getBool();
        $this->showJSErrors = true;//$showJSErrors->getBool();
        $this->scriptVersion = '1.2.3';//$siteScriptVersion->getString();
    }


    function emitJSRequired()
    {
        $jsVersion = $this->scriptVersion;
        $separator = ',';

        if(count($this->includeJSArray) == 0) {
            return "";
        }

        $url = "$jsVersion";

        $output = "<script type='text/javascript'>\n";

        foreach($this->includeJSArray as $includeJS) {
            //$output .= "setJSLoaded('".basename($includeJS).".js', false);\n";
            $url .= $separator;
            $url .= urlencode($includeJS);
        }

        $output .= "</script>\n";

        $domain = '';
        //if ($this->useCDNForScripts == true) {
//            $domain = $this->domain->getContentDomain(0);
        //}
        
        $uri = routeJSInclude($url);

        $output .= "<script type='text/javascript' src='".$domain.$uri."'></script>";

        return $output; 
    }



    /**
     * @param $media
     * @param $cssList CSSFile[]
     */
    function renderMediaCSS($mediaQuery, $cssList) {

        $fileList = '';
        $separator = '';

        foreach ($cssList as $cssFile) {
            /** @var $cssFile CSSFile */
            $fileList .= $separator;
            $fileList .= urlencode($cssFile->getFile());
            $separator = ',';
        }

        $domain = '';
        //if ($this->useCDNForScripts == true) {
//            $domain = $this->domain->getContentDomain(0);
        //}

        $mediaString = '';

        if ($mediaQuery) {
            $mediaString = " media='".$mediaQuery."' ";
        }

        $output = sprintf(
            "<link rel='stylesheet' type='text/css' %s href='/css/%s?%s' />\n",
            $mediaString,
            $fileList,
            $this->scriptVersion
        );

        return $output;
    }
    
    /**
     * @return string
     */
    function includeCSS() {

        if(count($this->cssFiles) == 0){
            return "";
        }


        $mediaCSS = [];

        foreach ($this->cssFiles as $cssFile) {
            $mediaCSS[$cssFile->getMediaQuery()][] = $cssFile;
        }
        
        $output = "";

        foreach ($mediaCSS as $media => $cssList) {
            $output .= $this->renderMediaCSS($media, $cssList);
        }

        return $output;
    }
}
