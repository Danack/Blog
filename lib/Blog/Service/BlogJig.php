<?php

namespace Blog\Service;

use Jig\Jig;
use Jig\JigConfig;
use Jig\Converter\JigConverter;
use Jig\JigException;

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
            'syntaxHighlighter',
            [$this, 'processSyntaxHighlighterStart'],
            [$this, 'processSyntaxHighlighterEnd']
        );
    }

    /**
     * @param JigConverter $jigConverter
     */
    public function processSyntaxHighlighterEnd(JigConverter $jigConverter)
    {
        $jigConverter->setLiteralMode(false);
        $jigConverter->addText("</pre>");
    }

    /**
     * @param JigConverter $jigConverter
     * @param $segmentText
     * @throws JigException
     */
    public function processSyntaxHighlighterStart(JigConverter $jigConverter, $segmentText)
    {
        $pattern = '#lang=[\'"]([\.\w]+)[\'"]#u';
        $matchCount = preg_match($pattern, $segmentText, $matches);
        if ($matchCount == 0) {
            throw new JigException("Could not extract lang from [$segmentText] for syntaxHighlighter.");
        }
    
        $lang = $matches[1];
    
        $srcFile = false;
    
        $pattern = '#file=[\'"]([\.\w-]+)[\'"]#u';
        $matchCount = preg_match($pattern, $segmentText, $matches);
        if ($matchCount != 0) {
            $srcFile = $matches[1];
        }

        $jigConverter->addText("<!-- SyntaxHighlighter Start -->");
    
        if ($srcFile) {
            //TODO - add error checking.
            $rawLink = "/staticFile/".$srcFile;
            $jigConverter->addText("\n\n<pre class='brush: $lang; toolbar: true;' data-link='$rawLink'>");
            $jigConverter->setLiteralMode(true);

            try {
                $contents = $this->sourceFileFetcher->fetch($srcFile);
            }
            catch (\Blog\Repository\SourceFileNotFoundException $sfnfe) {
                $contents = "Oops can't find source for: ".$srcFile;
            }
    
            $fileContents = htmlentities($contents, ENT_QUOTES);
            $fileContents = str_replace("<?php ", "&lt;php", $fileContents);
            $fileContents = str_replace("? >", "?&gt;", $fileContents);
    
            $jigConverter->addText($fileContents);
        }
        else {
            $jigConverter->addText("\n\n<pre class='brush: $lang; toolbar: true;'>");
            $jigConverter->setLiteralMode(true);
        }
    }
}
