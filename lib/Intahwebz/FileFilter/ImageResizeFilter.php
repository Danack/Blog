<?php


namespace Intahwebz\FileFilter;

use Intahwebz\File;

use Intahwebz\Image\GDImageLoader;


class ImageResizeFilter extends FileFilter {

    private $imageLoader;
    
    private $newSize;

    function __construct(
        FileFilter $previousFilter, 
        GDImageLoader $imageLoader, 
        File $outputFile,
        $newSize,
        $imageType,
        $filterUpdateMode = FileFilter::CHECK_EXISTS_MTIME_AND_PREVIOUS
    ) {
        $this->imageLoader = $imageLoader;
        $this->newSize = $newSize;
        $this->previousFilter = $previousFilter;
        $this->srcFile = $previousFilter->getFile();
        $this->destFile = $outputFile;
        $this->imageType = $imageType;
        $this->filterUpdateMode = $filterUpdateMode;
    }

    function filter($tmpName) {
        $image = $this->imageLoader->createImageFromFile($this->srcFile->getPath());
        $image->setResize($this->newSize);
        $image->saveImage($tmpName, $this->imageType);
    }
}

 