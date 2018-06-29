<?php

namespace Blog\Controller;

use Auryn\Injector;
use Blog\Model\ActiveBlogPost;
use Blog\Repository\BlogPostRepo;
use Blog\Response\HtmlResponse;
use Blog\Repository\BlogPostNotFoundException;
use Danack\Response\TextResponse;
use Twig_Environment as Twig;

class Blog
{
    /**
     * @return mixed
     */
    public function index(Twig $twig)
    {
        return new HtmlResponse($twig->render('pages/index.tpl'));
    }

//    public function perfTest()
//    {
//        return JigExecutable::create('pages/perfTest');
//    }

//    public function showDraft(
//        BlogDraftPath $storagePath,
//        $filename
//    ) {
//        $draftDirectory = $storagePath->getPath();
//        $blogPath = $draftDirectory."/".\Blog\App::ensureAbsoluteFilename($filename).".tpl.md";
//
//        $blogPost = new BlogPost();
//        $blogPost->blogPostID = 0;
//        $blogPost->title = str_replace("_", " ", $filename);
//
//        //TODO - add error detecttion.
//        $blogPost->blogPostText = file_get_contents($blogPath);
//        $blogPost->datestamp = date('Y-m-d');
//
//        $injectionParams = InjectionParams::fromShareObjects([
//            'Blog\Model\ActiveBlogPost' => new ActiveBlogPost($blogPost)
//        ]);
//
//        return JigExecutable::create('pages/displayBlogPost', $injectionParams);
//    }

//    public function showDrafts()
//    {
//        return JigExecutable::create('pages/drafts');
//    }

    public function display(
        BlogPostRepo $blogPostMapper,
        Injector $injector,
        Twig $twig,
        $blogPostID,
        $format = 'html'
    ) {

        try {
            $blogPost = $blogPostMapper->getBlogPost($blogPostID);
        }
        catch (BlogPostNotFoundException $bpnfe) {
            return new TextResponse("Blog post not found", 404);
        }

        if ($format == 'text') {
            return new TextResponse($blogPost->blogPostText);
        }

        $activeBlogPost = new ActiveBlogPost($blogPost);
        $params = ['Blog\Model\ActiveBlogPost' => $activeBlogPost];

        $injector->share($activeBlogPost);

        $html = $twig->render('pages/displayBlogPost.tpl');

        return new HtmlResponse($html);
    }
}
