<?php

namespace Blog\Controller;

use Blog\Repository\BlogPostRepo;
use Blog\Value\BlogDraftPath;
use Tier\InjectionParams; 
use Room11\HTTP\Body\TextBody;
use Blog\Model\ActiveBlogPost;
use Blog\Content\BlogPost;
use Tier\Tier;

class Blog
{
    /**
     * @return mixed
     */
    public function index()
    {
        return Tier::getRenderTemplateTier('pages/index');
    }
    
    
    public function perfTest()
    {
        return Tier::getRenderTemplateTier('pages/perfTest');
    }

    public function showDraft(
        BlogDraftPath $storagePath,
        $filename
    ) {
        $draftDirectory = $storagePath->getPath();// ->getSafePath('blogDraft');
        $blogPath = $draftDirectory."/".ensureAbsoluteFilename($filename).".tpl.md";

        $blogPost = new BlogPost();
        $blogPost->blogPostID = 0;
        $blogPost->title = str_replace("_", " ", $filename);
        
        //TODO - add error detecttion.
        $blogPost->blogPostText = file_get_contents($blogPath);
        $blogPost->datestamp = date('Y-m-d');
        
        $activeBlogPost = new ActiveBlogPost($blogPost);
        $params = ['Blog\Model\ActiveBlogPost' => $activeBlogPost];
        //$injectionParams = new InjectionParams();
        //InjectionParams::shareObjects($params);
        //$injectionParams->share*(
        
        return Tier::getRenderTemplateTier('pages/displayBlogPost', $params);
    }

    public function showDrafts()
    {
        return Tier::getRenderTemplateTier('pages/drafts');
    }


    public function display(
        BlogPostRepo $blogPostMapper,
        $blogPostID,
        $format = 'html'
    ) {
        $blogPost = $blogPostMapper->getBlogPost($blogPostID);
        if ($format == 'text') {
            return new TextBody($blogPost->blogPostText);
        }

        $activeBlogPost = new ActiveBlogPost($blogPost);
        $params = ['Blog\Model\ActiveBlogPost' => $activeBlogPost];

        return Tier::getRenderTemplateTier('pages/displayBlogPost', $params);
    }
}
