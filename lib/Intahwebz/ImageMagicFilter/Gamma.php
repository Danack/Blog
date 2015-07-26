<?php


namespace Intahwebz\ImageMagicFilter;

class Gamma extends ImageMagickFilter {

    private $gamma;

    function __construct(
        ImageMagickFilter $previousFilter = null,
        $gamma
    ) {
        $this->previousFilter = $previousFilter;
        $this->gamma = $gamma;

    }
    
    function filter(\Imagick $im){
        $im->gammaimage(
            $this->gamma
        );
    }
}

 