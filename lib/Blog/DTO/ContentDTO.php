<?php

namespace Blog\DTO;

class ContentDTO {
    public $contentID;
    public $datestamp;

    public function __construct($contentID = null, $datestamp = null) {
        $this->contentID = $contentID;
        $this->datestamp = $datestamp;
    } 
    function setContentID($contentID) { 
        $this->contentID = $contentID;
    }

    function setDatestamp($datestamp) { 
        $this->datestamp = $datestamp;
    }



    /**
     * @param $query \Intahwebz\TableMap\SQLQuery
     * @param $content \Blog\DB\ContentTable
     * @return int
     */
    function insertInto(\Intahwebz\TableMap\SQLQuery $query, \Blog\DB\ContentTable $content){

        $data = convertObjectToArray($this);
        $insertID = $query->insertIntoMappedTable($content, $data);
    $this->contentID = $insertID;

        return $insertID;
    }
}


