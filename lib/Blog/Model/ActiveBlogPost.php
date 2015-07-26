<?php


namespace Blog\Model;

use Blog\Content\BlogPost;

class ActiveBlogPost {

    /**
     * @var BlogPost
     */
    public $blogPost;
    
    public function __construct(BlogPost $blogPost)
    {
        $this->blogPost = $blogPost;
    }
}

