<?php


namespace Blog\Repository;

interface BlogPostRepo
{

    /**
     * @param $blogPostID
     * @return \Blog\Content\BlogPost
     * @throws \Exception
     */
    public function getBlogPost($blogPostID);

    /**
     * @param $year
     * @param $includeInactive
     * @return \Blog\Content\BlogPost[]
     * @throws \Exception
     */
    public function getBlogPostsForYear($year, $includeInactive);

    /**
     * @param $title
     * @param $isActive
     * @param $blogPostID
     * @throws \Exception
     */
    public function updateBlogPost($title, $isActive, $blogPostID);

    /**
     * @param $title
     * @param $text
     * @return int
     */
    public function createBlogPost($title, $text, $isActive);

    /**
     * @param $blogPostID
     * @param $text
     * @throws \Exception
     */
    public function updateBlogPostText($blogPostID, $text);
}
