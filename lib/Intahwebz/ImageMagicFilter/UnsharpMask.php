<?php


namespace Intahwebz\ImageMagicFilter;

class UnsharpMask extends ImageMagickFilter {

    private $radius;
    private $sigma;
    private $amount;
    private $threshold;

    function __construct(
        ImageMagickFilter $previousFilter = null,
        $radius, 
        $sigma, 
        $amount, 
        $threshold
    ) {
        $this->previousFilter = $previousFilter;
        $this->radius = $radius;
        $this->sigma = $sigma;
        $this->amount = $amount;
        $this->threshold = $threshold;
    }
    
    function filter(\Imagick $im){
        $im->unsharpMaskImage(
             $this->radius,
             $this->sigma,
             $this->amount,
             $this->threshold
        );
    }
}

 