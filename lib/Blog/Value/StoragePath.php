<?php


namespace Blog\Value;

use Tier\Path\Path;

class StoragePath extends Path
{
    private $path;

    public function __construct($path)
    {
        if ($path === null) {
            throw new \Exception(
                "Path cannot be null for class ".get_class($this)
            );
        }
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }
}
