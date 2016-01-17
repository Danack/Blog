<?php


namespace Blog\Model;

use Blog\Content\SourceFile;
use Blog\Route;

class TemplateSourceFile
{
    public function __construct(SourceFile $sourceFile)
    {
        $this->sourceFile = $sourceFile;
    }

    public function getFilename()
    {
        return $this->sourceFile->filename;
    }
    
    public function getText()
    {
        return $this->sourceFile->text;
    }

    public function getRoute()
    {
        $url = Route::blogSourceFile($this->sourceFile);
        return $url;
    }
}
