<?php

namespace Blog\DTO;

class BlogPostDTO {
    public $blogPostID;
    public $contentID;
    public $title;
    public $isActive;
    public $blogPostTextID;

    public function __construct($blogPostID = null, $contentID = null, $title = null, $isActive = null, $blogPostTextID = null) {
        $this->blogPostID = $blogPostID;
        $this->contentID = $contentID;
        $this->title = $title;
        $this->isActive = $isActive;
        $this->blogPostTextID = $blogPostTextID;
    } 
    function setBlogPostID($blogPostID) { 
        $this->blogPostID = $blogPostID;
    }

    function setContentID($contentID) { 
        $this->contentID = $contentID;
    }

    function setTitle($title) { 
        $this->title = $title;
    }

    function setIsActive($isActive) { 
        $this->isActive = $isActive;
    }

    function setBlogPostTextID($blogPostTextID) { 
        $this->blogPostTextID = $blogPostTextID;
    }

    /**
     * @param $query \Intahwebz\TableMap\SQLQuery
     * @param $blogPost \Blog\DB\BlogPostTable
     * @return int
     */
    function insertInto(\Intahwebz\TableMap\SQLQuery $query, \Blog\DB\BlogPostTable $blogPost){

        $data = convertObjectToArray($this);
        $insertID = $query->insertIntoMappedTable($blogPost, $data);
    $this->blogPostID = $insertID;

        return $insertID;
    }
}


