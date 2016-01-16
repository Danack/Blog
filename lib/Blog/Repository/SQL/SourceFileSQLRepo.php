<?php

namespace Blog\Repository\SQL;

use Blog\Repository\SourceFileRepo;
use Intahwebz\TableMap\SQLQueryFactory;
use Blog\DB\SourceFileTable;
use Blog\Repository\BlogPostNotFoundException;
use Blog\Repository\SourceFileNotFoundException;
use Blog\DTO\SourceFileDTO;

class SourceFileSQLRepo implements SourceFileRepo
{
    /**
     * @var SourceFileTable
     */
    private $sourceFileTable;

    /**
     * @var \Intahwebz\TableMap\SQLQueryFactory
     */
    private $sqlQueryFactory;

    public function __construct(
        SQLQueryFactory $sqlQueryFactory,
        SourceFileTable $sourceFileTable
    ) {
        $this->sqlQueryFactory = $sqlQueryFactory;
        $this->sourceFileTable = $sourceFileTable;
    }
    
    /**
     * @param $blogPostID
     * @throws BlogPostNotFoundException
     * @throws \Exception
     * @throws \Intahwebz\DB\DBException
     * @return \Blog\Content\BlogPost
     */
    public function getSourceFile($filename)
    {
        $sqlQuery = $this->sqlQueryFactory->create();
        $sqlQuery->table($this->sourceFileTable)->whereColumn('filename', $filename);
        $blogList = $sqlQuery->fetch();
        if (count($blogList) == 0) {
            throw new SourceFileNotFoundException("Could not find source file with filename $filename.");
        }

        $blogPost = castToObject('Blog\Content\SourceFile', $blogList[0]);

        return $blogPost;
    }


    /**
     * @param $title
     * @param $isActive
     * @param $blogPostID
     * @throws \Exception
     */
    public function updateSourceFile($sourceFileID, $filename, $text)
    {
        $sourceFileParams = array(
            'columns' => array(
                'filename' => $filename,
                'text' => $text
            ),
            'where' => array(
                'sourceFileID' => $sourceFileID
            )
        );
        $sqlQuery = $this->sqlQueryFactory->create();
        $sqlQuery->updateMappedTable($this->sourceFileTable, $sourceFileParams);
    }

    
    public function addSourceFile($filename, $text)
    {
        $sourceFileDTO = new SourceFileDTO(null, $filename, $text);
        $sqlQuery = $this->sqlQueryFactory->create();
        $data = convertObjectToArray($sourceFileDTO);
        $sourceFileID = $sqlQuery->insertIntoMappedTable($this->sourceFileTable, $data);

        return $sourceFileID;
    }
}
