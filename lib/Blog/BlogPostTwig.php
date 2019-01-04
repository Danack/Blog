<?php

declare(strict_types=1);

namespace Blog;


use Twig_Environment;
use Blog\Content\BlogPost;
use Michelf\Markdown;
use Michelf\MarkdownExtra;
use Twig_Error_Loader;
use Auryn\Injector;

class BlogPostTwig
{
    /** @var BlogPostLoader */
    private $blogPostLoader;

    /** @var \Twig_Environment */
    private $twig;

    public function __construct(Injector $injector)
    {
        $this->blogPostLoader = new BlogPostLoader();
        $this->twig = new Twig_Environment($this->blogPostLoader, array(
            'cache' => false,
            'strict_variables' => true,
            'debug' => true  // TODO - allow configuring
        ));

        addBlogFunctionsToTwig($this->twig, $injector);
    }

    /**
     * @param BlogPost $blogPost
     * @return string
     * @throws Twig_Error_Loader
     * @throws \Exception
     * @throws \Throwable
     * @throws \Twig_Error_Syntax
     */
    public function renderBlogPost(BlogPost $blogPost)
    {
        // Apparently we could use $twig->createTemplate() instead of fucking around with loaders.
        $this->blogPostLoader->setBlogPost($blogPost);

        $template = $this->twig->createTemplate($blogPost->getText());
        $twigHtml = $template->render([]);
//        $twigHtml = $this->twig->render('blogpost_' . $blogPost->getBlogPostID(), []);
        $html = MarkdownExtra::defaultTransform($twigHtml);

        return $html;
    }
}
