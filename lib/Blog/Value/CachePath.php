<?php


namespace Blog\Value;

use FileFilter\File;

class CachePath
{
    private $path;

    function __construct($string) {
        if ($string == null) {
            throw new \Exception("Path cannot be null for class ".get_class($this));
        }
        $this->path = $string;
    }
    
    function getFile($directory, $file, $extension)
    {
        return new File($this->path.'/'.$directory.'/', $file, $extension);
    }

    function getPath() {
        return $this->path;
    }
}
