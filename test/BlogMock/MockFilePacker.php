<?php

namespace BlogMock;

use ScriptHelper\FilePacker;

class MockFilePacker implements FilePacker {

    function pack($outputFilename, $jsIncludeArray, $appendLine, $extension)
    {
        $filename = $this->getFinalFilename($jsIncludeArray, $extension);
        @mkdir(dirname($filename), 0755, true);
        file_put_contents($filename, "This is a packed file.");

        return $filename;
    }

    function getHeaders()
    {
        return ['Mock' => 'Mock'];
    }

    function getFinalFilename(array $files, $extension)
    {
        $filename = __DIR__."/../tmp/".implode('_', $files).".".$extension;
        return $filename;
    }
}

