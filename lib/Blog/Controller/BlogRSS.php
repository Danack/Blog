<?php

namespace Blog\Controller;

use Blog\App;
use Blog\Value\StoragePath;
use Blog\Repository\BlogPostRepo;
use Blog\Model\TemplateBlogPostFactory;
use Danack\Response\TextResponse;
use Room11\HTTP\Body\FileBody;
use UniversalFeedCreator;
use Blog\Route;

class BlogRSS
{
    public function rssFeed(
        // StoragePath $storagePath,
        BlogPostRepo $blogPostRepo// ,
        // TemplateBlogPostFactory $templateBlogPostFactory
    ) {
//        $filePath = $storagePath->getPath()."/cache/rss/feed.xml";
//        if (!@file_exists($filePath) || @filemtime($filePath) < time() - 7200) {
//            $this->genFeed($filePath, $blogPostRepo, $templateBlogPostFactory);
//        }

        $feedText = $this->genFeed(/* $filePath,*/ $blogPostRepo);

        $headers = [
            'Content-Type' => 'text/plain'
        ];


        return new TextResponse($feedText, $headers);

//        $fileBody = new FileBody(
//            $filePath,
//            "application/xml; charset=UTF-8; filename=feed.xml"
//        );
//
//        return $fileBody;
    }
    
    private function genFeed(
        // $filePath,
        BlogPostRepo $blogPostRepo//,
//        TemplateBlogPostFactory $templateBlogPostFactory
    ) {
        //TODO validate with http://validator.w3.org/feed/
        $rss = new UniversalFeedCreator();

        $rss->title = "Danack's blog";
        $rss->description = "Coding, photography and brain dumps.";
        $rss->descriptionTruncSize = 500; //optional
        $rss->descriptionHtmlSyndicated = true;
        $rss->link = "http://blog.basereality.com/";
        $rss->syndicationURL = "http://blog.basereality.com/rss";

        $year = 2014;
        
        $blogPostsList = $blogPostRepo->getBlogPostsForYear($year, false);

        if (count($blogPostsList) == 0) {
            throw new \Exception("No blog posts found.");
        }
        
        foreach ($blogPostsList as $blogPost) {
            $item = new \FeedItem();
            $item->title = $blogPost->getTitle();
            $item->link = Route::blogPost($blogPost);

            $item->description = $blogPost->getText();

            //$templateBlogPost = $templateBlogPostFactory->create($blogPost);
            //$item->description = $templateBlogPost->showPreview(400);

            //optional
            $item->descriptionTruncSize = 500;
            $item->descriptionHtmlSyndicated = true;
            $item->date = $blogPost->datestamp;
            $item->source = "http://blog.basereality.com/rss";
            $item->author = "Dan Ackroyd";

            $rss->addItem($item);
        }

        //App::ensureDirectoryExists($filePath);
        //$rss->saveFeed("RSS1.0", $filePath, false);

        return $rss->createFeed("RSS1.0");
    }
}
