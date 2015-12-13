<?php

namespace Blog\Controller;

use Blog\Mapper\BlogPostMapper;
use Intahwebz\StoragePath;
use Room11\HTTP\Body\TextBody;
use Blog\Model\ActiveBlogPost;
use Blog\Content\BlogPost;

class Blog
{
    /**
     * @return mixed
     */
    public function index()
    {
        return \Tier\getRenderTemplateTier('pages/index');
    }

    public function showDraft(
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

        return \Tier\getRenderTemplateTier('pages/displayBlogPost', $params);
    }

    public function showDrafts()
    {
        return \Tier\getRenderTemplateTier('pages/drafts');
    }

    /**
     * @param BlogPostMapper $blogPostMapper
     * @param TemplateResponseFactory $templateResponseFactory
     * @param $blogPostID
     * @param $format
     * @return RedirectResponse|mixed
     */
    public function display(
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

        return \Tier\getRenderTemplateTier('pages/displayBlogPost', $params);
    }
}
