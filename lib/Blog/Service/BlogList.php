<?php


namespace Blog\Service;

use Blog\Repository\BlogPostRepo;
use Blog\Model\TemplateBlogPostFactory;
use Blog\Site\LoginStatus;

class BlogList
{
//    /**
//     * @var TemplateBlogPostFactory
//     */
//    private $templateBlogPostFactory;

    /**
     * @var BlogPostRepo
     */
    private $blogPostMapper;

//    /**
//     * @var LoginStatus
//     */
//    private $loginStatus;
    
    public function __construct(
        //TemplateBlogPostFactory $templateBlogPostFactory,
        BlogPostRepo $blogPostMapper
        //LoginStatus $loginStatus
    ) {
        $this->blogPostMapper = $blogPostMapper;
     //   $this->templateBlogPostFactory = $templateBlogPostFactory;
     //   $this->loginStatus = $loginStatus;
    }

    /**
     * @return \Blog\Content\BlogPost[]
     */
    public function getBlogs()
    {
//        $showInactive = $this->loginStatus->isLoggedIn();
        $showInactive = true;
        $blogPosts = $this->blogPostMapper->getBlogPostsForYear(2015, $showInactive);
//        $templateBlogPosts = [];
//        foreach ($blogPosts as $blogPost) {
//            $templateBlogPosts[] = $this->templateBlogPostFactory->create($blogPost);
//        }
        
        return $blogPosts;
    }
}
