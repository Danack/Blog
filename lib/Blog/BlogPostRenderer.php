<?php

declare(strict_types=1);

namespace Blog;

use Blog\Content\BlogPost;


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
        $finalHtml = $this->blogPostTwig->renderBlogPost($blogPost);

        $endPreviewPosition = strpos($finalHtml, "<!-- end_preview -->");
        if ($endPreviewPosition !== false) {
            return substr($finalHtml, 0, $endPreviewPosition);
        }

        return substr($finalHtml, 0, 200);
    }
}
