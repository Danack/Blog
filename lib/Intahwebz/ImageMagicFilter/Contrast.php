<?php


namespace Intahwebz\ImageMagicFilter;


class Contrast extends ImageMagickFilter {

    private $blackPoint;
    private $whitePoint;

    function __construct(
        ImageMagickFilter $previousFilter = null,
        $blackPoint,
        $whitePoint
    ) {
        $this->previousFilter = $previousFilter;
        $this->$blackPoint = $blackPoint;
        $this->$whitePoint = $whitePoint;
    }
    
    function filter(\Imagick $im){
        $im->contrastStretchImage(
            $this->$blackPoint, 
            $this->$whitePoint
        );
    }
}

 