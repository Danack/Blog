<?php

namespace Blog\Service\SourceFileFetcher;

use Blog\Service\SourceFileFetcher;
use Blog\Repository\SourceFileRepo;

class RepoSourceFileFetcher implements SourceFileFetcher
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
