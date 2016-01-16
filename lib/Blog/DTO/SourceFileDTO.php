<?php

namespace Blog\DTO;


class SourceFileDTO {


    public $sourceFileID;
    public $filename;
    public $text;

    public function __construct($sourceFileID, $filename, $sourceFileText) {
        $this->sourceFileID = $sourceFileID;
        $this->filename = $filename;
        $this->text = $sourceFileText;
    } 

    function setSourceFileID($sourceFileID) { 
        $this->sourceFileID = $sourceFileID;
    }
    
    function setFilename($filename) { 
        $this->filename = $filename;
    }

    function setText($sourceFileText) { 
        $this->text = $sourceFileText;
    }

    /**
     * @param \Intahwebz\TableMap\SQLQuery $query
     * @param \Blog\DB\SourceFileTable $blogPostText
     * @return int
     * @throws \Exception
     */
    function insertInto(
        \Intahwebz\TableMap\SQLQuery $query,
        \Blog\DB\SourceFileTable $blogPostText
    ) {
        $data = convertObjectToArray($this);
        $insertID = $query->insertIntoMappedTable($blogPostText, $data);
        $this->sourceFileID = $insertID;

        return $insertID;
    }
}
