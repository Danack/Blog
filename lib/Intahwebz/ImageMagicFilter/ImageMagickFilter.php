<?php


namespace Intahwebz\ImageMagicFilter;


abstract class ImageMagickFilter {

    use \Intahwebz\SafeAccess;
    /**
     * @var ImageMagickFilter
     */
    protected $previousFilter = null;

    abstract function filter(\Imagick $im);

    function process(\Imagick $im) {

        if ($this->previousFilter != null) {
            $this->previousFilter->process($im);
        }

        $this->filter($im);
    }
    
}

 