<?php

namespace Blog\TemplatePlugin;

use Auryn\Injector;
use Blog\Content\BlogPost;
use Blog\Site\CodeHighlighter;
use Blog\Service\BlogJig;
use Jig\Plugin;
use Jig\Plugin\BasicPlugin;
use Jig\Jig;
use Michelf\MarkdownExtra;

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
    
    private $currentHighlightLang = null;
    
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

    public function highlightCodeBlockRenderStart($segmentText)
    {
        $this->currentHighlightLang = BlogJig::extractLanguage($segmentText);

        $html = <<< 'HTML'
  <div class="tab-content codeContent" style="position: relative;" >
    <div style="position: relative;"  class="codeHolder">
      <div class="borderTestOuter">
        <div class="borderTest"></div>    
        </div>
        <pre class="code">
HTML;

        return $html;
    }
    
    /**
     * @param $content
     * @return string
     */
    public function highlightCodeBlockRenderEnd($content)
    {
        $text = CodeHighlighter::highlight($content, $this->currentHighlightLang);
        $text .= '</pre></div></div>';

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
