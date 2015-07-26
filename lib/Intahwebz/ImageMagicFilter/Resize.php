<?php


namespace Intahwebz\ImageMagicFilter;




class Resize extends ImageMagickFilter {

    private $columns;
    private $rows;
    private $filter;
    private $blur;

    function __construct(
        ImageMagickFilter $previousFilter = null, 
        $width, 
        $height, 
        $filter = \Imagick::FILTER_GAUSSIAN, 
        $blur = 0.5
    ) {
        $this->previousFilter = $previousFilter;
        $this->columns = $width;
        $this->rows = $height;
        $this->filter = $filter;
        $this->blur = $blur;
    }
    
    function filter(\Imagick $im){
        $im->resizeImage(
            $this->columns, 
            $this->rows,
            $this->filter, 
            $this->blur
        );
    }


}

 