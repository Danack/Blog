<?php

namespace Blog\Content;

use Intahwebz\Cache\KeyName;


class SourceFile
{
    use KeyName;

    public $sourceFileID;
    public $filename;
    public $text;

    public static function create($sourceFileID, $filename, $text)
    {
        $instance = new self();
        $instance->sourceFileID = $sourceFileID;
        $instance->filename = $filename;
        $instance->text = $text;

        return $instance;
    }

    public function getCacheKey($name)
    {
        return $this->getClassKey($name.'_'.$this->sourceFileID);
    }
}
