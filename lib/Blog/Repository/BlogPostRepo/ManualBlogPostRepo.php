<?php

declare(strict_types=1);

namespace Blog\Repository\BlogPostRepo;

use Blog\Content\BlogPost;
use Blog\Repository\BlogPostRepo;


class ManualBlogPostRepo implements BlogPostRepo
{
    /** @var \Blog\Content\BlogPost[]  */
    private $blogPosts = [];

    public function add($id, $title, $file, $date)
    {
        $path = __DIR__ . '/../../../../posts/'. $file;
        $content = file_get_contents($path);

        $blogPost = BlogPost::create(
            $id, //$blogPostID,
            $title, // $title,
            $content, //$text,
            $date, // $datestamp,
            true //$isActive
        );

        array_unshift($this->blogPosts, $blogPost);
    }

    public function getBlogPost($blogPostID)
    {
        foreach ($this->blogPosts as $blogPost) {
            if ($blogPost->getBlogPostID() == $blogPostID) {
                return $blogPost;
            }
        }

        throw new \Exception("Blog post ID $blogPostID not found.");
    }

    public function getBlogPostsForYear($year, $includeInactive)
    {
        return array_reverse($this->blogPosts);
    }

    public function updateBlogPost($title, $isActive, $blogPostID)
    {
        throw new \Exception("// TODO: Implement updateBlogPost() method.");
    }

    public function createBlogPost($title, $text, $isActive)
    {
        throw new \Exception("// TODO: Implement createBlogPost() method.");
    }

    public function updateBlogPostText($blogPostID, $text)
    {
        throw new \Exception("TODO: Implement updateBlogPostText() method.");
    }
}
