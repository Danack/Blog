<?php


namespace Blog\Model;

//Blah - not used.
class SourceFileList implements \IteratorAggregate
{
    private $fileList;
    
    public function __contruct(array $filelist)
    {
        $this->filelist = $filelist;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->fileList);
    }
}
