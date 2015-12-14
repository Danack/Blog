<?php


namespace Blog;

/**
 * Class Routes
 * Oi, Australians, cut out the tittering. 
 * @package Blog
 */
class Routes
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
}
