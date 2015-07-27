<?php


namespace BaseReality\Service;

use Blog\Mapper\BlogPostMapper;
use Blog\Model\TemplateBlogPostFactory;
use Blog\Service\LoginStatus;

class BlogList
{
    /**
     * @var TemplateBlogPostFactory
     */
    private $templateBlogPostFactory;

    /**
     * @var BlogPostMapper
     */
    private $blogPostMapper;
    
    /**
     * @var LoginStatus
     */
    private $loginStatus;
    
    public function __construct(
        TemplateBlogPostFactory $templateBlogPostFactory,
        BlogPostMapper $blogPostMapper,
        LoginStatus $loginStatus
    ) {
        $this->blogPostMapper = $blogPostMapper;
        $this->templateBlogPostFactory = $templateBlogPostFactory;
        $this->loginStatus = $loginStatus;
    }

    /**
     * @return \\Blog\Model\TemplateBlogPost[]
     */
    public function getBlogs()
    {
        $showInactive = $this->loginStatus->isLoggedIn();
        $blogPosts = $this->blogPostMapper->getBlogPostsForYear(2013, $showInactive);
        $templateBlogPosts = [];
        foreach ($blogPosts as $blogPost) {
            $templateBlogPosts[] = $this->templateBlogPostFactory->create($blogPost);
        }
        
        return $templateBlogPosts;
    }
}
