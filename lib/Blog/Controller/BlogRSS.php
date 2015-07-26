<?php

namespace Blog\Controller;

use Intahwebz\StoragePath;
use Blog\Mapper\BlogPostMapper;
use Blog\Model\TemplateBlogPostFactory;

use Tier\ResponseBody\FileBody;
use UniversalFeedCreator;


class BlogRSS {

    function rssFeed(
        StoragePath $storagePath,
        BlogPostMapper $blogPostMapper,
        TemplateBlogPostFactory $templateBlogPostFactory
    
    ) {
        $filePath = $storagePath->getPath()."/cache/rss/feed.xml";
        if (!@file_exists($filePath) || @filemtime($filePath) < time() - 7200) {
            $this->genFeed($filePath, $blogPostMapper, $templateBlogPostFactory);
        }

        $fileBody = new FileBody(
            $filePath,
            "application/xml; charset=UTF-8; filename=feed.xml"
        );

        return $fileBody;
    }
    
    private function genFeed($filePath, BlogPostMapper $blogPostMapper,
        TemplateBlogPostFactory $templateBlogPostFactory)
    {
        //TODO validate with http://validator.w3.org/feed/
        $rss = new UniversalFeedCreator();

        $rss->title = "Danack's blog";
        $rss->description = "Coding, photography and brain dumps.";
        $rss->descriptionTruncSize = 500; //optional
        $rss->descriptionHtmlSyndicated = true;
        $rss->link = "http://blog.basereality.com/";
        $rss->syndicationURL = "http://blog.basereality.com/rss";

        //$year = date('Y');
        $year = 2014;
        
        $blogPostsList = $blogPostMapper->getBlogPostsForYear($year, false);

        if (count($blogPostsList) == 0) {
            throw new \Exception("No blog posts found.");
        }
        
        foreach ($blogPostsList as $blogPost) {
            $item = new \FeedItem();
            $item->title = $blogPost->getTitle();
            $item->link = routeBlogPost($blogPost->blogPostID);
            $templateBlogPost = $templateBlogPostFactory->create($blogPost);
            $item->description = $templateBlogPost->showPreview(400);

            //optional
            $item->descriptionTruncSize = 500;
            $item->descriptionHtmlSyndicated = true;
            $item->date = $blogPost->datestamp;
            $item->source = "http://blog.basereality.com/rss";
            $item->author = "Dan Ackroyd";

            $rss->addItem($item);
        }

        ensureDirectoryExists($filePath);
        $rss->saveFeed("RSS1.0", $filePath, false);
    }
    
}

