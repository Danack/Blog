<?php

namespace Blog\Model;

use Auryn\Injector;
use Blog\App;
use Blog\Content\BlogPost;
use Blog\Route;
use Intahwebz\ObjectCache;
use Jig\Jig;
use Zend\Escaper\Escaper;

class TemplateBlogPost
{
    /**
     * @var BlogPost
     */
    public $blogPost;

    /**
     * @var Jig
     */
    private $jig;
    
    private $escaper;

    const SYNTAX_START  =  "<!-- SyntaxHighlighter Start -->";

    public function __construct(
        BlogPost $blogPost,
        ObjectCache $objectCache,
        Jig $jig,
        Injector $injector,
        Escaper $escaper
    ) {
        $this->blogPost = $blogPost;
        $this->objectCache = $objectCache;
        $this->jig = $jig;
        $this->injector = $injector;

        $this->jig->addDefaultPlugin('Blog\TemplatePlugin\BlogPlugin');
        $this->jig->addDefaultPlugin('Blog\TemplatePlugin\BlogPostPlugin');
        $this->escaper = $escaper;
    }

    public function renderTitle()
    {
        $url = Route::blogPost($this->blogPost);

//        Escaper::
//        escapeHtml
        
        return sprintf(
            "<a href='%s'>%s</a>",
            $this->escaper->escapeHtmlAttr($url),
            $this->escaper->escapeHtml($this->blogPost->title)
        );
    }

    public function renderDate($includeYear = false)
    {
        $dateFormat = 'jS M';
        if ($includeYear == true) {
            $dateFormat = $dateFormat." Y";
        }

        return App::formatDateString($this->blogPost->datestamp, $dateFormat);
    }

    public function showPreview($length = 400)
    {
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

    public function showPreviewInternal($length = 400)
    {
        //TODO - wrap this
        $objectID = $this->blogPost->getClassKey($this->blogPost->blogPostID);
        $className = $this->jig->getParsedTemplateFromString($this->blogPost->blogPostText, $objectID);
        $fullText = $this->injector->execute([$className, 'render']);

        //We don't preview code that is in SyntaxHighlighter.
        //TODO - modify {SyntaxHighlither} plugin to just drop this content.
        $syntaxStartPos = mb_strpos($fullText, self::SYNTAX_START);
        if ($syntaxStartPos !== false) {
            if ($syntaxStartPos < $length) {
                $fullText = trim(mb_substr($fullText, 0, $syntaxStartPos));
                $fullText .= "...";
            }
        }

        $fullText = preg_replace("#<[^>]*>#", "", $fullText); //remove any HTML tags

        if (mb_strlen($fullText) > $length) {
            $fullText = trim(mb_substr($fullText, 0, $length));
            $fullText .= "...";
        }

        return $fullText;
    }
}
