<?php


namespace Blog\Mapper;

use Blog\Content\BlogPost;

class BlogPostMapperMock implements BlogPostMapper
{
    /**
     * @param $blogPostID
     * @return \BaseReality\Content\BlogPost
     * @throws \Exception
     */
    public function getBlogPost($blogPostID)
    {
        //$blogPost = new \Blog\Content\BlogPost();
        
        $blogPost = BlogPost::create(
            $blogPostID,
            $title = "Hello world",
            $text = "This is a template",
            $datestamp = '2014-05-28 02:06:40',
            $isActive = true,
            $blogPostTextID = $blogPostID
        );

        return $blogPost;
    }

    /**
     * @param $year
     * @param $includeInactive
     * @return \BaseReality\DisplayableContent\DisplayableBlogPost[]
     * @throws \Exception
     * @throws \Intahwebz\DB\DBException
     */
    public function getBlogPostsForYear($year, $includeInactive)
    {
        $blogPostID = getNextBlogPostID();

        $blogPost = BlogPost::create(
            $blogPostID,
            $title = "Hello world",
            $text = "This is a template",
            $datestamp = '2014-05-28 02:06:40',
            $isActive = true,
            $blogPostTextID = $blogPostID
        );
        
        return [$blogPost];
    }

    /**
     * @param $title
     * @param $isActive
     * @param $blogPostID
     * @throws \Exception
     */
    public function updateBlogPost($title, $isActive, $blogPostID)
    {
        //throw new \Exception("Not implemented");
    }

    /**
     * @param $title
     * @param $text
     * @return int
     */
    public function createBlogPost($title, $text, $isActive)
    {
        throw new \Exception("Not implemented");
    }

    /**
     * @param $blogPostID
     * @param $text
     * @throws \Exception
     */
    public function updateBlogPostText($blogPostID, $text)
    {
        //throw new \Exception("Not implemented");
    }
}
