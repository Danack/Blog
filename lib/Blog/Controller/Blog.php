<?php

namespace Blog\Controller;

use Auryn\Injector;
use Blog\Model\ActiveBlogPost;
use Blog\Repository\BlogPostRepo;
use Blog\Repository\BlogPostNotFoundException;
use SlimAuryn\Response\TextResponse;
use SlimAuryn\Response\HtmlResponse;
use Twig\Environment as Twig;
use Blog\Service\BlogList;
use Blog\BlogPostRenderer;
use Blog\MarkdownRenderer\MarkdownRenderer;

class Blog
{
    /**
     * @return mixed
     */
    public function index(BlogList $blogList, MarkdownRenderer $markdownRenderer)
    {
        $html = showFrontPage($blogList, $markdownRenderer);
        return new HtmlResponse($html);
    }

    public function display(
        BlogList $blogList,
        MarkdownRenderer $markdownRenderer,
        BlogPostRepo $blogPostMapper,
        Injector $injector,
//        Twig $twig,
        $blogPostID,
        $format = 'html'
    ) {

        try {
            $blogPost = $blogPostMapper->getBlogPost($blogPostID);
        }
        catch (BlogPostNotFoundException $bpnfe) {
            return new TextResponse("Blog post not found", [], 404);
        }

        if ($format == 'text') {
            return new TextResponse($blogPost->blogPostText);
        }

        $activeBlogPost = new ActiveBlogPost($blogPost);
//        $params = ['Blog\Model\ActiveBlogPost' => $activeBlogPost];

        $injector->share($activeBlogPost);

//        $html = $twig->render('pages/displayBlogPost.tpl');
        $html = showBlogPostPage(
            $blogList,
            $markdownRenderer,
            $blogPost
        );

        return new HtmlResponse($html);
    }
}
