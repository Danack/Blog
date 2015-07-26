<?php


namespace Intahwebz\ImageMagicFilter;


class SetFormat extends ImageMagickFilter {

    private $format;

    function __construct(
        ImageMagickFilter $previousFilter = null,
        $format
    ) {
        $this->previousFilter = $previousFilter;
        $this->format = $format;
    }
    
    function filter(\Imagick $im){
        $im->setImageFormat(
            $this->format
        );
    }


}

 