<?php

namespace Blog\Service;

use Jig\Jig;
use Jig\JigConfig;
use Jig\Converter\JigConverter;
use Jig\JigException;
use Blog\Site\CodeHighlighter;

class BlogJig extends Jig
{
    /**
     * @var SourceFileFetcher
     */
    private $sourceFileFetcher;

    public function __construct(
        SourceFileFetcher $sourceFileFetcher,
        JigConfig $jigConfig,
        JigConverter $jigConverter = null
    ) {
        parent::__construct($jigConfig, $jigConverter);
        $this->sourceFileFetcher = $sourceFileFetcher;
        $this->addDefaultPlugin('Blog\TemplatePlugin\BlogPostPlugin');
        $this->bindCompileBlock(
            'syntaxHighlighterFile',
            [$this, 'processSyntaxHighlighterStart'],
            [$this, 'processSyntaxHighlighterEnd']
        );
    }

    /**
     * @param JigConverter $jigConverter
     */
    public function processSyntaxHighlighterEnd(JigConverter $jigConverter)
    {
        $jigConverter->setLiteralMode(null);
        $jigConverter->addText('</pre></div> </div>');
    }

    public static function extractLanguage($segmentText)
    {
        $pattern = '#lang=[\'"]([\.\w]+)[\'"]#u';
        $matchCount = preg_match($pattern, $segmentText, $matches);
        if ($matchCount == 0) {
            return null;
        }
        $lang = $matches[1];
        
        return $lang;
    }
    
    /**
     * @param JigConverter $jigConverter
     * @param $segmentText
     * @throws JigException
     */
    public function processSyntaxHighlighterStart(JigConverter $jigConverter, $segmentText)
    {
        $lang = self::extractLanguage($segmentText);
        $srcFile = false;
    
        $pattern = '#file=[\'"]([\.\w-]+)[\'"]#u';
        $matchCount = preg_match($pattern, $segmentText, $matches);
        if ($matchCount != 0) {
            $srcFile = $matches[1];
        }

        if (!$srcFile) {
            throw new \Exception("syntax highlight without file is no longer supported. use syntaxHighlightCode instead.");
        }

        try {
            $contents = $this->sourceFileFetcher->fetch($srcFile);
        }
        catch (\Blog\Repository\SourceFileNotFoundException $sfnfe) {
            $contents = "Oops can't find source for: ".$srcFile;
        }

        $rawLink = "/sourceFile/".$srcFile;
        $jigConverter->addText('<div><div class="tab-content codeContent" style="position: relative">');
        
        $rawLinkHTML = <<< HTML
  <a href="$rawLink" class="linkToCode">
    Raw text
  </a>
HTML;

        $jigConverter->addText($rawLinkHTML);
        $jigConverter->addText('<pre class="code">');
        $jigConverter->setLiteralMode('SyntaxHighlighter');
        $fileContents = CodeHighlighter::highlight($contents, $lang);
        $jigConverter->addText($fileContents);
    }
}
