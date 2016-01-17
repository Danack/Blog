<?php

namespace Blog;

use Blog\Content\BlogPost;
use Blog\Content\SourceFile;

/**
 * Class Route 
 * Oi, Australians, cut out the tittering. 
 * @package Blog
 */
class Route
{
    public static function index()
    {
        return "/";
    }

    /**
     * @param $filename
     * @param string $size
     * @return string
     */
    public static function staticImage($filename, $size = 'original')
    {
        $imageName = $filename;
        $sizeString = $size;
        return "/staticImage/".$sizeString."/".urlencode($imageName);
    }

    public static function showUpload()
    {
        return "/upload";
    }
    
    public function listSourceFiles()
    {
    return "/listFiles";
    }
    
    public static function showUploadFile()
    {
        return "/uploadFile";
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

    public static function showDraft($draftFilename)
    {
        return sprintf('/draft/%s', $draftFilename);
    }

    public static function blogPostWithFormat($blogPostID, $format)
    {
        return sprintf('/blog/%d.%d', $blogPostID, $format);
    }
    
    public static function jsInclude($commaSeparatedValues)
    {
        return "/js/".$commaSeparatedValues;
    }
    
    public static function blogEdit($blogPostID)
    {
        return "/blogedit/".$blogPostID;
    }
    
    public static function blogReplace($blogPostID)
    {
        return "/blogreplace/".$blogPostID;
    }
    
    public static function blogSourceFile(SourceFile $sourceFile)
    {
        return "/sourceFile/".$sourceFile->filename;
    }
    
}
