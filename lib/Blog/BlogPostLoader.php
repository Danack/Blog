<?php

declare(strict_types=1);

namespace Blog;

use Twig\Environment;
use Blog\Content\BlogPost;
use Michelf\Markdown;
use Twig_Extension_StringLoader;
use Twig_Loader_Filesystem;
//use Twig\LoaderInterface;
//use Twig_SourceContextLoaderInterface;
//use Twig_Source;
use Twig_Error_Loader;
use Twig\Source as TwigSource;

use Twig\Loader\LoaderInterface;
//use Twig\Loader\SourceContextLoaderInterface;

class BlogPostLoader implements /* SourceContextLoaderInterface, */ LoaderInterface
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
     * @return TwigSource
     * @throws Twig_Error_Loader
     */
    public function getSourceContext($name): TwigSource
    {
//        if (false === $template = $this->getTemplate($name)) {
//            throw new Twig_Error_Loader(sprintf('Template "%s" does not exist.', $name));
//        }

//        $html = Markdown::defaultTransform($this->blogPost->getText());

        $html = $this->blogPost->getText();

        return new TwigSource($html, $this->blogPost->getTitle());
    }

//    public function exists($name)
//    {
//        //return (bool)$this->getTemplate($name);
//        return true;
//    }

//    public function getCacheKey($name): string
//    {
////        return 'blog_post_id_' . $this->blogPost->getBlogPostID();
//        return false;
//    }
//
//    public function isFresh($name, $time): bool
//    {
//        return false;
//    }
}