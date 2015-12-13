<?php

namespace Blog\TemplatePlugin;

use Jig\Plugin;
use Jig\Plugin\BasicPlugin;
use Intahwebz\Utils\ScriptInclude;
use Blog\Content\BlogPost;
use Jig\Jig;
use Auryn\Injector;
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
    
    public function __construct(
        ScriptInclude $scriptInclude,
        Jig $jig,
        Injector $injector
    ) {
        $this->scriptInclude = $scriptInclude;
        $this->jig = $jig;
        $this->injector = $injector;
    }

    public static function hasBlock($blockName)
    {
        $blockList = static::getBlockRenderList();
        $parentBlockList = parent::getBlockRenderList();
        $blockList = array_merge($parentBlockList, $blockList);

        return in_array($blockName, $blockList, true);
    }

    public static function getBlockRenderList()
    {
        return [
            'markdown',
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


    public function markdownBlockRenderStart($segmentText)
    {
        return "";
    }
    
    public function markdownBlockRenderEnd($contents)
    {
        $result = MarkdownExtra::defaultTransform($contents);

        return $result;
    }

    public function blogPostBody(BlogPost $blogPost)
    {
        $this->jig->addDefaultPlugin('Blog\TemplatePlugin\BlogPostPlugin');
        $className = $this->jig->getParsedTemplateFromString(
            $blogPost->blogPostText,
            "BlogPost".$blogPost->blogPostID
        );
        $contents = $this->injector->execute([$className, 'render']);

        return $contents;
    }
}
