<?php


namespace Intahwebz;

class File
{

    public $directory;

    public $filename;

    private $extension;

    public function __construct($directory, $filename, $extension)
    {
        $this->directory = $directory;
        $this->filename = $filename;
        $this->extension = $extension;
    }
    
    static public function fromFullPath($fullPath)
    {
        $fileInfo = pathinfo($fullPath);

        return new self(
            $fileInfo['dirname'],
            $fileInfo['basename'],
            $fileInfo['extension']
        );
    }

    public function getPath()
    {
        $path = $this->directory.'/'.$this->filename;

        if ($this->extension != null) {
            $path .= '.'.$this->extension; 
        }
        
        return $path;
    }
    
    /**
     * @param $extension
     * @param bool $prefix
     * @return File
     */
    public function addExtension(
        $extension,
        $prefix = true
    ) {
        return new File($this->directory, $this->filename, $extension.'.'.$this->extension);
    }
}