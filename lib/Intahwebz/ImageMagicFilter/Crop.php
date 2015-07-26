<?php


namespace Intahwebz\ImageMagicFilter;

class Crop extends ImageMagickFilter {

    private $width;
    private $height;
    private $x;
    private $y;

    function __construct(
        ImageMagickFilter $previousFilter = null, 
        $width, 
        $height, 
        $x, 
        $y
    ) {
        $this->previousFilter = $previousFilter;
        $this->width = $width;
        $this->height = $height;
        $this->x = $x;
        $this->y = $y;
    }
    
    function filter(\Imagick $im){
        $im->cropImage(
            $this->width, 
            $this->height,
            $this->x, 
            $this->y
        );
    }
}

 