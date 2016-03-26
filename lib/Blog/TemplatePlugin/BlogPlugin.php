<?php

namespace Blog\TemplatePlugin;

use Blog\Content\BlogPost;
use Blog\Model\TemplateBlogPostFactory;
use Blog\Route;
use Blog\Service\BlogJig;
use Jig\Plugin;
use Jig\Plugin\BasicPlugin;
use ScriptHelper\ScriptInclude;
use Blog\Site\CodeHighlighter;

class BlogPlugin extends BasicPlugin
{
    /**
     * @var \ScriptHelper\ScriptInclude
     */
    private $scriptInclude;

    /**
     * @var \Blog\Model\TemplateBlogPostFactory
     */
    private $templateBlogPostFactory;
    
    private $currentHighlightLang;
    
    
    public function __construct(
        ScriptInclude $scriptInclude,
        TemplateBlogPostFactory $templateBlogPostFactory
    ) {
        $this->scriptInclude = $scriptInclude;
        $this->templateBlogPostFactory = $templateBlogPostFactory;
    }

    /**
     * @return array
     */
    public static function getFunctionList()
    {
        $functions = [
            'htmlOptions',
            'linkTier',
            'makeRenderableBlogPost',
            'peakMemory',
            'routeIndex',
            'routeBlogPost',
            'routeBlogPostWithFormat',
            'routeJSInclude',
            'routeShowUpload',
            'routeShowDrafts',
            'routeShowDraft',
            'routeBlogReplace',
            'routeBlogEdit',
            'showTweetButton',
            'var_dump',

            'articleImage'
        ];

        $parentFunctions = parent::getFunctionList();

        return array_merge($functions, $parentFunctions);
    }

    public static function getBlockRenderList()
    {
        $blocks = parent::getBlockRenderList();
        $blocks[] = 'syntaxHighlight';
        $blocks[] = 'syntaxHighlighter';

        return $blocks;
    }
    
    /**
     * @return string
     */
    public function peakMemory()
    {
        $output = "<span style='font-size: 8px; display: block;'>Peak memory ";
        $output .= number_format(memory_get_peak_usage());
        $output .= "</span>";

        return $output;
    }

    public function showTweetButton()
    {
        $text = <<< END
<a href='https://twitter.com/share' class='twitter-share-button' data-via='MrDanack' data-dnt='true'>Tweet</a>
END;
 
        $this->scriptInclude->addBodyLoadJS("addTwitterDelayed();");

        return $text;
    }

    /**
     * @param $name
     * @param $valueDescriptions
     * @return string
     */
    public function htmlOptions($name, $valueDescriptions)
    {
        $output = '';
        $output .= "<select name='$name'>";
        foreach ($valueDescriptions as $value => $description) {
            //$output .= "<option label='Joe Schmoe' value='1800'>Joe Schmoe</option>";
            //TODO - needs escaping.
            $output .= "<option value='$value'>$description</option>";
        }
        $output .= "</select>";

        echo $output;
    }

    public function var_dump($value)
    {
        var_dump($value);
    }
    
    public function makeRenderableBlogPost(BlogPost $blogPost)
    {
        return $this->templateBlogPostFactory->create($blogPost);
    }

    public function routeIndex()
    {
        return Route::index();
    }

    public function routeBlogPost($blogPostID)
    {
        return Route::blogPost($blogPostID);
    }
    
    public function routeBlogPostWithFormat($blogPostID, $format)
    {
        return Route::blogPostWithFormat($blogPostID, $format);
    }

    public function routeJSInclude($jsFile)
    {
        return Route::jsInclude($jsFile);
    }
    
    public function routeShowUpload()
    {
        return Route::showUpload();
    }
    
    public function routeShowDrafts()
    {
        return Route::showDrafts();
    }
    
    public function routeShowDraft($draftFilename)
    {
        return Route::showDraft($draftFilename);
    }

    public function routeBlogEdit($blogPostID)
    {
        return Route::blogEdit($blogPostID);
    }
    
    public function linkTier()
    {
        return "<a href='https://github.com/danack/tier'>Tier</a>";
    }
    
    
    public function routeBlogReplace($blogPostID)
    {
        return Route::blogReplace($blogPostID);
    }

    public function articleImage($imageFilename, $size, $float = 'left', $description = false)
    {
        return \Blog\App::articleImage($imageFilename, $size, $float, $description);
    }

    public function syntaxHighlightBlockRenderStart($segmentText)
    {
        return $this->syntaxHighlighterBlockRenderStart($segmentText);
    }

    /**
     * @param $content
     * @return string
     */
    public function syntaxHighlightBlockRenderEnd($content)
    {
        return $this->syntaxHighlighterBlockRenderEnd($content);
    }

    public function syntaxHighlighterBlockRenderStart($segmentText)
    {
        $this->currentHighlightLang = BlogJig::extractLanguage($segmentText);

        $html = <<< 'HTML'
  <div class="tab-content codeContent" style="position: relative;" >
    <div style="position: relative;" class="codeHolder" >
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
    public function syntaxHighlighterBlockRenderEnd($content)
    {
        $text = CodeHighlighter::highlight($content, $this->currentHighlightLang);
        $text .= '</pre></div></div>';

        return $text;
    }
    
}
