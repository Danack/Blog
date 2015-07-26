<?php


namespace Blog\Mapper;

interface BlogPostMapper {

    /**
     * @param $blogPostID
     * @return \Blog\Content\BlogPost
     * @throws \Exception
     */
    function getBlogPost($blogPostID);

    /**
     * @param $year
     * @param $includeInactive
     * @return \Blog\Content\BlogPost[]
     * @throws \Exception
     */
    function getBlogPostsForYear($year, $includeInactive);

    /**
     * @param $title
     * @param $isActive
     * @param $blogPostID
     * @throws \Exception
     */
    function updateBlogPost($title, $isActive, $blogPostID);

    /**
     * @param $title
     * @param $text
     * @return int
     */
    function createBlogPost($title, $text);

    /**
     * @param $blogPostID
     * @param $text
     * @throws \Exception
     */
    function updateBlogPostText($blogPostID, $text);
}

