<?php


namespace Intahwebz\ImageMagicFilter;

class SetImageCompression extends ImageMagickFilter {

    private $quality;
    //private $setJPEG;

    function __construct(
        ImageMagickFilter $previousFilter = null,
        $quality//,
        //$setJPEG
    ) {
        $this->previousFilter = $previousFilter;
        $this->quality = $quality;
        //$this->setJPEG = $setJPEG;
    }
    
    function filter(\Imagick $im){
        $im->setImageCompressionQuality($this->quality);
    }
}

 