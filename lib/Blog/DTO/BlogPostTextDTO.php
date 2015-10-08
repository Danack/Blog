<?php

namespace Blog\DTO;


class BlogPostTextDTO {
    public $blogPostTextID;
    public $blogPostText;

    public function __construct($blogPostTextID = null, $blogPostText = null) {
        $this->blogPostTextID = $blogPostTextID;
        $this->blogPostText = $blogPostText;
    } 
    function setBlogPostTextID($blogPostTextID) { 
        $this->blogPostTextID = $blogPostTextID;
    }

    function setBlogPostText($blogPostText) { 
        $this->blogPostText = $blogPostText;
    }

    /**
     * @param $query \Intahwebz\TableMap\SQLQuery
     * @param $blogPostText \Blog\DB\BlogPostTextTable
     * @return int
     */
    function insertInto(\Intahwebz\TableMap\SQLQuery $query, \Blog\DB\BlogPostTextTable $blogPostText){

        $data = convertObjectToArray($this);
        $insertID = $query->insertIntoMappedTable($blogPostText, $data);
    $this->blogPostTextID = $insertID;

        return $insertID;
    }
}


