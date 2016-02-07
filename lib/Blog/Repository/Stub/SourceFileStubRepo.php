<?php

namespace Blog\Repository\Stub;

use Blog\Repository\SourceFileRepo;
//use Intahwebz\TableMap\SQLQueryFactory;
//use Blog\DB\SourceFileTable;
//use Blog\Repository\BlogPostNotFoundException;
//use Blog\Repository\SourceFileNotFoundException;
//use Blog\DTO\SourceFileDTO;

use Blog\Content\SourceFile;

class SourceFileSQLRepo implements SourceFileRepo
{
    /**
     * @inherit
     */
    public function getSourceFile($filename)
    {
        $blogPost = new SourceFile(1, 'foo.txt', "This is a stub source file");

        return $blogPost;
    }

    /**
     * @inherit
     */
    public function updateSourceFile($sourceFileID, $filename, $text)
    {
    }

    public function addSourceFile($filename, $text)
    {
        return 1;
    }

    public function getAllSourceFiles()
    {
        $sourceFiles = [];
        $sourceFiles[] = $this->getSourceFile('foo.txt');

        return $sourceFiles;
    }
}
