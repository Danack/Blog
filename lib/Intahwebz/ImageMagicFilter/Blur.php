<?php


namespace Intahwebz\ImageMagicFilter;




class Blur extends ImageMagickFilter {

    private $radius;
    private $sigma;

    function __construct(
        ImageMagickFilter $previousFilter = null,
        $radius,
        $sigma
    ) {        
        $this->previousFilter = $previousFilter;
        $this->radius = $radius;
        $this->sigma = $sigma;
    }
    
    function filter(\Imagick $im){
        $im->blurimage(
            $this->radius, 
            $this->sigma
        );
    }


}

 