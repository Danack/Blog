<?php

namespace BlogStub;

use Intahwebz\FileFetcher;

class StubFileFetcher implements FileFetcher
{
    private $filenamesAndPaths;

    public function __construct($filenamesAndPaths = [])
    {
        $this->filenamesAndPaths = $filenamesAndPaths;
    }

    public function hasUploadedFile($formFileName)
    {
        return array_key_exists($formFileName, $this->filenamesAndPaths);
    }

    /**
     * @param $formFileName
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function getUploadedFile($formFileName)
    {
        return $this->filenamesAndPaths[$formFileName];
    }
}
