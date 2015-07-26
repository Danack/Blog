<?php

namespace Intahwebz\Utils;


class MockFileFetcher implements \Intahwebz\FileFetcher {

    function __construct($filename, $originalFilename) {
        $this->filename = $filename;
        $this->originalFilename = $originalFilename;
    }


    function getUploadedFile($formFileName) {
        $tempFilename = tempnam ('/tmp', 'mockFileFetcher');

        if (file_exists($this->filename) == false) {
            throw new \InvalidArgumentException("File ".$this->filename." does not exist.");
        }

        copy($this->filename, $tempFilename);

        return new \Intahwebz\UploadedFile(
            $this->originalFilename,
            $tempFilename,
            filesize($tempFilename)
        );
    }
}




 