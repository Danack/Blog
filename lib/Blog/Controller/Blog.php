<?php

namespace Blog\Controller;

use Blog\Mapper\BlogPostMapper;
use Intahwebz\Response\RedirectResponse;
use Intahwebz\StoragePath;
use Intahwebz\Response\TemplateResponseFactory;

use Arya\TextBody;
use Blog\Model\ActiveBlogPost;
use Blog\Content\BlogPost;

class Blog {

    /**
     * @return mixed
     */
    function index() {
        return getRenderTemplateTier('pages/index');
    }

    function showDraft(
        StoragePath $storagePath, 
        $filename
    ) {
        $draftDirectory = $storagePath->getSafePath('blogDraft');
        $blogPath = $draftDirectory."/".ensureAbsoluteFilename($filename).".tpl.md";

        $blogPost = new BlogPost();
        $blogPost->blogPostID = 0;
        $blogPost->title = str_replace("_", " ", $filename);
        
        //TODO - add error detecttion.
        $blogPost->blogPostText = file_get_contents($blogPath);
        $blogPost->datestamp = date('Y-m-d');
        
        $activeBlogPost = new ActiveBlogPost($blogPost);
        $params = ['Blog\Model\ActiveBlogPost' => $activeBlogPost];

        return getRenderTemplateTier('pages/displayBlogPost', $params);
    }

    function showDrafts()
    {
        return getRenderTemplateTier('pages/drafts');
    }

    /**
     * @param BlogPostMapper $blogPostMapper
     * @param TemplateResponseFactory $templateResponseFactory
     * @param $blogPostID
     * @param $format
     * @return RedirectResponse|mixed
     */
    function display(
        BlogPostMapper $blogPostMapper,
        $blogPostID,
        $format = 'html'
    ) {
        $blogPost = $blogPostMapper->getBlogPost($blogPostID);
        if ($format == 'text') {
            return new TextBody($blogPost->blogPostText);
        }

        $activeBlogPost = new ActiveBlogPost($blogPost);
        $params = ['Blog\Model\ActiveBlogPost' => $activeBlogPost];

        return getRenderTemplateTier('pages/displayBlogPost', $params);
    }
}

