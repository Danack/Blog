<?php


namespace Intahwebz\ImageMagicFilter;

class ModulateImage extends ImageMagickFilter {

    private $brightness;
    private $saturation;
    private $hue;

    function __construct(
        ImageMagickFilter $previousFilter = null,
        $brightness, 
        $saturation, 
        $hue
    ) {
        $this->previousFilter = $previousFilter;
        $this->brightness = $brightness;
        $this->saturation = $saturation;  
        $this->hue = $hue;
    }
    
    function filter(\Imagick $im){
        $im->modulateImage(
            $this->brightness,
            $this->saturation,
            $this->hue
        );
    }
}

 