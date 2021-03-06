<?php

namespace Blog\Value;

class BlogDraftPath
{
    private $path;

    function __construct($string) {
        if ($string == null) {
            throw new \Exception("Path cannot be null for class ".get_class($this));
        }
        $this->path = $string;
    }

    function getPath() {
        return $this->path;
    }
}
