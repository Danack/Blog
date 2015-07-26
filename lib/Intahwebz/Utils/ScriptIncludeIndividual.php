<?php

namespace Intahwebz\Utils;

class ScriptIncludeIndividual extends ScriptInclude {

    function emitJSRequired() {
        
        $jsVersion = $this->scriptVersion;
        $output = '';

        foreach($this->includeJSArray as $includeJS) {
//            $output .= "<script type='text/javascript'>\n";
//            $output .= "setJSLoaded('".$includeJS."', false);\n";
//            $output .= "</script>\n";
            $output .= "<script type='text/javascript' src='/js/".$includeJS.".js?version=$jsVersion'></script>\n";
        }

//        $output .= "<script type='text/javascript'>\n";
//        $output .= "dumpFailedJS();\n";
//        $output .= "</script>\n";

        return $output;
    }
    
    /**
     * @return string
     */
    function includeCSS()
    {
        $output = "";

        foreach ($this->cssFiles as $cssFile) {
            $mediaString = '';

            if ($cssFile->mediaQuery) {
                $mediaString = " media='".$cssFile->mediaQuery."' ";
            }
    
            $output .= sprintf(
                "<link rel='stylesheet' type='text/css' %s href='/css/%s.css?%s' />\n",
                $mediaString,
                $cssFile->file,
                $this->scriptVersion
            );
        }

        return $output;
    }
    
    
        /**
     * @param $media
     * @param $cssList CSSFile[]
     */
    function renderMediaCSS($mediaQuery, $cssList) {

        $output = '';
        $separator = ',';

        foreach ($cssList as $cssFile) {
            /** @var $cssFile CSSFile */
            $output .= $separator;
            $output .= urlencode($cssFile->getFile());
        }

        $domain = '';
//        if ($this->useCDNForScripts == true) {
//            $domain = $this->domain->getContentDomain(0);
//        }

        $mediaString = '';

        if ($mediaQuery) {
            $mediaString = " media='".$mediaQuery."' ";
        }

        $output .= sprintf(
            "<link rel='stylesheet' type='text/css' %s href='/css/%s?'%s />\n",
            $mediaString,
            $output,
            $this->scriptVersion
        );

        return $output;
    }
    
}

 