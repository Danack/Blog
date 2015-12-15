<?php


namespace Blog;

use Blog\Content\BlogPost;

/**
 * Class Routes
 * Oi, Australians, cut out the tittering. 
 * @package Blog
 */
class Route
{
    public static function showUpload()
    {
        return "/upload";
    }

    public static function showDrafts()
    {
        return "/drafts";
    }

    public static function templateViewer()
    {
        return "/templateViewer";
    }
    
    public static function blogPost(BlogPost $blogPost)
    {
        $name = str_replace(' ', '_', $blogPost->title);
        $name = preg_replace("#[^a-zA-Z0-9_]#", '', $name);
        
        return sprintf('/blog/%d/%s', $blogPost->blogPostID, $name);
    }
}
