<?php

declare(strict_types=1);

namespace Blog\Repository\SourceFileRepo;

use Blog\Repository\SourceFileRepo;

use Blog\Content\SourceFile;

class FileBasedSourceFileRepo implements SourceFileRepo
{
    public function getSourceFile($filename)
    {

        $path =  __DIR__ . "/../../../../files/" . $filename;

        $contents = @file_get_contents($path);

        if ($contents === false) {
            throw new \Exception("Could not open file $filename for blog.");
        }

        return SourceFile::create(
            $sourceFileID = 123,
            $filename,
            $text = $contents
        );
    }

    public function addSourceFile($filename, $text)
    {
        throw new \Exception("addSourceFile not implemented yet.");
    }

    public function updateSourceFile($sourceFileID, $filename, $text)
    {
        throw new \Exception("updateSourceFile not implemented yet.");
    }

    public function getAllSourceFiles()
    {
        throw new \Exception("getAllSourceFiles not implemented yet.");
    }
}
