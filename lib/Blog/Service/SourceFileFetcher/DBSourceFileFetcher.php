<?php

namespace Blog\Service\SourceFileFetcher;

use FileFilter\StorageDownloadFilter;
use Blog\Value\CachePath;
use FileFilter\Storage;
use Blog\Service\SourceFileFetcher;
use Blog\Repository\SourceFileRepo;

class DBSourceFileFetcher implements SourceFileFetcher
{
    private $sourceFileRepo;

    public function __construct(SourceFileRepo $sourceFileRepo)
    {
        $this->sourceFileRepo = $sourceFileRepo;
    }

    /**
     * @param $srcFile
     * @return string
     */
    public function fetch($srcFile)
    {
        $sourceFile = $this->sourceFileRepo->getSourceFile($srcFile);
        
        return $sourceFile->text;
    }
}
