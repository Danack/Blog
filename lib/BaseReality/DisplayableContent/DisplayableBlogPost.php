<?php


namespace BaseReality\DisplayableContent;

use Intahwebz\ObjectCache;
use Intahwebz\Route;

use BaseReality\Content\BlogPost;
use Jig\JigRender;

class DisplayableBlogPost implements \Intahwebz\DisplayableContent {

    /**
     * @var \BaseReality\Content\BlogPost
     */
    public $blogPost;

    //TODO needs to be in a plugin
    const SYNTAX_START  =  "<!-- SyntaxHighlighter Start -->";

//    /**
//     * @var \Intahwebz\Route
//     */
//    public $route;

//    /**
//     * @var \Intahwebz\Domain
//     */
//    public $domain;

    /**
     * @var JigRender
     */
    protected $jigRender;

    /**
     * @var \Intahwebz\ObjectCache
     */
    private $objectCache;


    static public function fromBlogPost(
        BlogPost $blogPost,
//        Route $route,
//        \Intahwebz\Domain $domain,
        JigRender $jigRender,
        ObjectCache $objectCache
    ) {
        $instance = new self();
        $instance->blogPost = $blogPost;
//        $instance->route = $route;
//        $instance->domain = $domain;
        $instance->jigRender = $jigRender;
        $instance->objectCache = $objectCache;
        
        return $instance;
    }
    
    
    function getContentID() {
        return $this->blogPost->getContentID();
    }

    function getDOMID() {
        return $this->blogPost->getDOMID();
    }

    function displayPreview() {
        $url = $this->getContentURL();
        return $this->blogPost->renderTitleLink($url);
    }

    function displayThumbnail() {
        $url = $this->getContentURL();
        return $this->blogPost->renderTitleLink($url);
    }

    function getDisplayableVersion() {
        return $this->blogPost;
    }

    function renderTitleLink() {
        $url = $this->getContentURL();
        return $this->blogPost->renderTitleLink($url);
    }

    function getDate() {
        return $this->renderDate();
    }
    
    function renderDate() {
        return $this->blogPost->displayDate(true);
    }
    
    function getContentURL() {
        $params = [
            'blogPostID' => $this->blogPost->blogPostID,
        ];

        return $this->route->generateURL(
            $params,
            $this->domain
        );
    }

    function showPreview($length = 400) {
        $cacheKey = $this->blogPost->getCacheKey('preview');

        $cachedVersion = $this->objectCache->get($cacheKey);
        if ($cachedVersion) {
            return $cachedVersion;
        }
        $fullText = $this->showPreviewInternal($length);

        if ($fullText) {
            $this->objectCache->put($cacheKey, $cachedVersion, 240);
        }
        return $fullText;
    }

    function showPreviewInternal($length = 400) {
        //TODO - wrap this
        $objectID = $this->blogPost->getClassKey($this->blogPost->blogPostID);
        $fullText = $this->jigRender->renderTemplateFromString($this->blogPost->blogPostText, $objectID);
        
        //We don't preview code that is in SyntaxHighlighter.
        //TODO - modify {SyntaxHighlither} plugin to just drop this content.
        $syntaxStartPos = mb_strpos($fullText, self::SYNTAX_START);
        if ($syntaxStartPos !== false) {
            if($syntaxStartPos < $length) {
                $fullText = trim(mb_substr($fullText, 0, $syntaxStartPos));
                $fullText .= "...";
            }
        }

        $fullText = preg_replace ("#<[^>]*>#", "", $fullText); //remove any HTML tags

        if (mb_strlen($fullText) > $length) {
            $fullText = trim(mb_substr($fullText, 0, $length));
            $fullText .= "...";
        }

        return $fullText;
    }

    /**
     * @return string
     * @throws \Exception
     * @throws \Jig\JigException
     */
    function renderBody() {
        //TODO - wrap this
        $objectID = $this->blogPost->getClassKey($this->blogPost->blogPostID);
        $objectID = $objectID."nocache";

        return $this->jigRender->renderTemplateFromString($this->blogPost->blogPostText, $objectID);
    }

    /**
     * @return mixed
     */
    function getTitle() {
        return $this->blogPost->title;
    }

    /**
     * @param bool $includeYear
     * @return bool|string
     */
    function displayDate($includeYear = false) {
        return $this->blogPost->displayDate($includeYear);
    }
}




 