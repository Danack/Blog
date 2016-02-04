<?php

namespace Blog\TemplatePlugin;

use Jig\Plugin;
use Jig\Plugin\BasicPlugin;
use Blog\Content\BlogPost;
use Jig\Jig;
use Auryn\Injector;
use Michelf\MarkdownExtra;
use Blog\Site\CodeHighlighter;

class BlogPostPlugin extends BasicPlugin
{
    /**
     * @var Jig
     */
    private $jig;

    /**
     * @var \Auryn\Injector
     */
    private $injector;
    
    public function __construct(
        Jig $jig,
        Injector $injector
    ) {
        $this->jig = $jig;
        $this->injector = $injector;
    }

    public static function getBlockRenderList()
    {
        return [
            'markdown',
            'highlightCode',
            'highlightTemplate',
        ];
    }

    /**
     * @return array
     */
    public static function getFunctionList()
    {
        $functions = [
            'blogPostTitle',
            'blogPostBody',
            'blogPostDate',
        ];

        $parentFunctions = parent::getFunctionList();

        return array_merge($functions, $parentFunctions);
    }

    public function highlightCodeBlockRenderStart($extraParam)
    {
        return '<div class="tab-content codeContent"><pre class="code">';
    }
    
    public static function highlightCodeBlockRenderEnd($contents)
    {
        $text = trim(CodeHighlighter::highlight(trim($contents)));
        $text .= '</pre></div>';

        return $text;
    }
    

    public function markdownBlockRenderStart($segmentText)
    {
        return "";
    }
    
    public function markdownBlockRenderEnd($contents)
    {
        $result = MarkdownExtra::defaultTransform($contents);

        return $result;
    }

    function getBlogPostUniqueName(BlogPost $blogPost)
    {
        return "BlogPost\\BlogPost".$blogPost->blogPostID."_".$blogPost->blogPostTextID;
    }

    public function blogPostBody(BlogPost $blogPost)
    {
        $this->jig->addDefaultPlugin('Blog\TemplatePlugin\BlogPostPlugin');
        $className = $this->jig->getParsedTemplateFromString(
            $blogPost->blogPostText,
            $this->getBlogPostUniqueName($blogPost)
        );
        $contents = $this->injector->execute([$className, 'render']);

        return $contents;
    }
}
