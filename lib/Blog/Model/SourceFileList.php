<?php


namespace Blog\Model;

use Blog\Repository\SourceFileRepo;
use Blog\Model\TemplateSourceFile;

class SourceFileList implements \IteratorAggregate
{
    private $fileList;
    
    public function __construct(SourceFileRepo $sourceFileRepo)
    {
        $files = $sourceFileRepo->getAllSourceFiles();

        $this->fileList = [];
        foreach ($files as $file) {
            $this->fileList[] = new TemplateSourceFile($file);
        }
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->fileList);
    }
}
