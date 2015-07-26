<?php

namespace BlogStub;

use Intahwebz\FileFetcher;



class StubFileFetcher implements FileFetcher {
    
    private $filenamesAndPaths;

    function __construct($filenamesAndPaths = [])
    {
        $this->filenamesAndPaths = $filenamesAndPaths;
    }

    function hasUploadedFile($formFileName)
    {
        return array_key_exists($formFileName, $this->filenamesAndPaths);
    }

    /**
     * @param $formFileName
     * @return mixed
     * @throws \InvalidArgumentException
     */
    function getUploadedFile($formFileName)
    {
        return $this->filenamesAndPaths[$formFileName];
    }
}