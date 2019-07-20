<?php

declare(strict_types=1);

namespace Blog;

use Twig_Environment;
use Blog\Content\BlogPost;
use Michelf\Markdown;
use Twig_Extension_StringLoader;
use Twig_Loader_Filesystem;
use Twig_LoaderInterface;
use Twig_SourceContextLoaderInterface;
use Twig_Source;
use Twig_Error_Loader;

use Twig\Loader\LoaderInterface;
use Twig\Loader\SourceContextLoaderInterface;

class BlogPostLoader implements SourceContextLoaderInterface, LoaderInterface
{
    /** @var BlogPost  */
    private $blogPost;

//    public function __construct(BlogPost $blogPost)
//    {
////        $this->blogPost = $blogPost;
//    }

    public function setBlogPost(BlogPost $blogPost)
    {
        $this->blogPost = $blogPost;
    }

    public function getSource($name)
    {
        throw new \Exception("getSource not implemented yet.");
    }


    /**
     * @param string $name
     * @return Twig_Source
     * @throws Twig_Error_Loader
     */
    public function getSourceContext($name)
    {
//        if (false === $template = $this->getTemplate($name)) {
//            throw new Twig_Error_Loader(sprintf('Template "%s" does not exist.', $name));
//        }

//        $html = Markdown::defaultTransform($this->blogPost->getText());

        $html = $this->blogPost->getText();



        return new Twig_Source($html, $this->blogPost->getTitle());
    }

    public function exists($name)
    {
        //return (bool)$this->getTemplate($name);
        return true;
    }

    public function getCacheKey($name)
    {
//        return 'blog_post_id_' . $this->blogPost->getBlogPostID();
        return false;
    }

    public function isFresh($name, $time)
    {
        return false;
    }
}