<?php

namespace Blog\Controller;

use Blog\Content\BlogPost;
use Blog\Model\ActiveBlogPost;
use Blog\Repository\BlogPostRepo;
use Blog\Value\BlogDraftPath;
use Blog\Repository\BlogPostNotFoundException;
use Room11\HTTP\Body\TextBody;
use Tier\Bridge\JigExecutable;
use Tier\InjectionParams; 


class Blog
{
    /**
     * @return mixed
     */
    public function index()
    {
        return JigExecutable::create('pages/index');
    }

    public function perfTest()
    {
        return JigExecutable::create('pages/perfTest');
    }

    public function showDraft(
        BlogDraftPath $storagePath,
        $filename
    ) {
        $draftDirectory = $storagePath->getPath();
        $blogPath = $draftDirectory."/".\Blog\App::ensureAbsoluteFilename($filename).".tpl.md";

        $blogPost = new BlogPost();
        $blogPost->blogPostID = 0;
        $blogPost->title = str_replace("_", " ", $filename);
        
        //TODO - add error detecttion.
        $blogPost->blogPostText = file_get_contents($blogPath);
        $blogPost->datestamp = date('Y-m-d');
        
        $injectionParams = InjectionParams::fromShareObjects([
            'Blog\Model\ActiveBlogPost' => new ActiveBlogPost($blogPost)
        ]);

        return JigExecutable::create('pages/displayBlogPost', $injectionParams);
    }

    public function showDrafts()
    {
        return JigExecutable::create('pages/drafts');
    }

    public function display(
        BlogPostRepo $blogPostMapper,
        $blogPostID,
        $format = 'html'
    ) {
        try {
            $blogPost = $blogPostMapper->getBlogPost($blogPostID);
        }
        catch (BlogPostNotFoundException $bpnfe) {
            return new TextBody("Blog post not found", 404);
        }

        if ($format == 'text') {
            return new TextBody($blogPost->blogPostText);
        }

        $activeBlogPost = new ActiveBlogPost($blogPost);
        $params = ['Blog\Model\ActiveBlogPost' => $activeBlogPost];

        return JigExecutable::createWithSharedObjects('pages/displayBlogPost', $params);
    }
}
