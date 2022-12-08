<?php

declare(strict_types=1);

namespace Blog;

use Blog\Content\BlogPost;

echo "screw BlogPostRenderer";
exit(-1);

class BlogPostRenderer
{
    /** @var BlogPostTwig */
    private $blogPostTwig;

    /**
     *
     * @param \Blog\BlogPostTwig $blogPostTwig
     */
    public function __construct(\Blog\BlogPostTwig $blogPostTwig)
    {
        $this->blogPostTwig = $blogPostTwig;
    }


    /**
     * @param BlogPost $blogPost
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderBlogPost(BlogPost $blogPost)
    {
        $finalHtml = $this->blogPostTwig->renderBlogPost($blogPost);

        return $finalHtml;
    }


    /**
     * @param BlogPost $blogPost
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderBlogPostPreview(BlogPost $blogPost)
    {
        return renderBlogPostPreview($this->blogPostTwig, $blogPost);
    }
}
