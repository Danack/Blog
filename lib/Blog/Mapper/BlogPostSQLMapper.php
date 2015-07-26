<?php


namespace Blog\Mapper;

use Intahwebz\TableMap\SQLQueryFactory;

use Blog\DB\ContentTable;
use Blog\DB\BlogPostTable;
use Blog\DB\BlogPostTextTable;
use Blog\DTO\BlogPostTextDTO;
use Blog\DTO\BlogPostDTO;


class BlogPostSQLMapper implements BlogPostMapper {

    /**
     * @var ContentTable
     */
    private $contentTable;

    /**
     * @var BlogPostTable
     */
    private $blogPostTable;

    /**
     * @var BlogPostTextTable
     */
    private $blogPostTextTable;


    /**
     * @var \Intahwebz\TableMap\SQLQueryFactory
     */
    private $sqlQueryFactory;

    function __construct(
        SQLQueryFactory $sqlQueryFactory,
        ContentTable $contentTable,
        BlogPostTable $blogPost,
        BlogPostTextTable $blogPostText
    ) {
        $this->sqlQueryFactory = $sqlQueryFactory;
        $this->contentTable = $contentTable;
        $this->blogPostTable = $blogPost;
        $this->blogPostTextTable = $blogPostText;
    }

    /**
     * @param $blogPostID
     * @return \BaseReality\Content\BlogPost
     */
    function getBlogPost($blogPostID) {
        $sqlQuery = $this->sqlQueryFactory->create();
        $sqlQuery->table($this->blogPostTextTable);
        $sqlQuery->table($this->blogPostTable)->wherePrimary($blogPostID);
        $sqlQuery->table($this->contentTable);
        $blogList = $sqlQuery->fetch();

        if (count($blogList) == 0) {
            throw new BlogPostNotFoundException("Could not find blog post $blogPostID.");
        }

        $blogPost = castToObject('Blog\Content\BlogPost', $blogList[0]);

        return $blogPost;
    }

    /**
     * @param $year
     * @param $includeInactive
     * @return \Blog\Content\BlogPost[]
     * @throws \Exception
     * @throws \Intahwebz\DB\DBException
     */
    function getBlogPostsForYear($year, $includeInactive) {
        $sqlQuery = $this->sqlQueryFactory->create();
        $sqlQuery->table($this->blogPostTextTable);
        if ($includeInactive == true) {
            $sqlQuery->table($this->blogPostTable);
        }
        else{
            $sqlQuery->table($this->blogPostTable)->whereColumn('isActive', 1);
        }

        $contentTableAlias = $sqlQuery->table($this->contentTable);
        //$contentTableAlias->whereColumnFunction('YEAR', 'datestamp',  $year);
        $sqlQuery->order($contentTableAlias, 'datestamp', 'DESC');
        $blogList = $sqlQuery->fetch();

        return castArraysToObjects('Blog\Content\BlogPost', $blogList);
    }

    /**
     * @param $title
     * @param $isActive
     * @param $blogPostID
     * @throws \Exception
     */
    function updateBlogPost($title, $isActive, $blogPostID){
        $blogPostParams = array(
            'columns' => array(
                'title'	=> $title,
                'isActive'	=> $isActive,
            ),
            'where' => array(
                'blogPostID' => $blogPostID
            )
        );
        $sqlQuery = $this->sqlQueryFactory->create();
        $sqlQuery->updateMappedTable($this->blogPostTable, $blogPostParams);
    }

    /**
     * @param $title
     * @param $text
     * @return int
     * @throws \Exception
     */
    function createBlogPost($title, $text) {
        $blogPostTextDTO = new BlogPostTextDTO();
        $sqlQuery = $this->sqlQueryFactory->create();

        $blogPostTextDTO->blogPostText = $text;
        $data = convertObjectToArray($blogPostTextDTO);
        $blogPostTextID = $sqlQuery->insertIntoMappedTable($this->blogPostTextTable, $data);

        $blog = new BlogPostDTO();
        $blog->title = $title;
        $blog->blogPostTextID = $blogPostTextID;
        $data = convertObjectToArray($blog);
        $contentID = $sqlQuery->insertIntoMappedTable($this->contentTable, $data);
        $data['contentID'] = $contentID;

        return $sqlQuery->insertIntoMappedTable($this->blogPostTable, $data);
    }

    /**
     * @param $blogPostID
     * @param $text
     * @throws \Exception
     */
    function updateBlogPostText($blogPostID, $text) {
        $blogPostText = new BlogPostTextDTO();
        $blogPostText->blogPostText = $text;

        $data = convertObjectToArray($blogPostText);
        $sqlQuery = $this->sqlQueryFactory->create();
        $blogPostTextID = $sqlQuery->insertIntoMappedTable($this->blogPostTextTable, $data);

        $blogPostParams = array(
            'columns' => array(
                'blogPostTextID' => $blogPostTextID,
            ),
            'where' => array(
                'blogPostID' => $blogPostID
            )
        );

        $sqlQuery = $this->sqlQueryFactory->create();
        $sqlQuery->updateMappedTable($this->blogPostTable, $blogPostParams);
    }
}

