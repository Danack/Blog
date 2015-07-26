<?php


namespace Blog\Service;
use Jig\Jig;
use Jig\JigConfig;
use Jig\JigRender;
use Jig\Converter\JigConverter;
use Jig\JigException;


class BlogJig extends Jig {

    /**
     * @var SourceFileFetcher
     */
    private $sourceFileFetcher;

    public function __construct(
        SourceFileFetcher $sourceFileFetcher,
        JigConfig $jigConfig,
        JigRender $jigRender = null,
        JigConverter $jigConverter = null)
    {
        parent::__construct($jigConfig, $jigRender, $jigConverter);
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
    function processSyntaxHighlighterEnd(JigConverter $jigConverter)
    {
        $jigConverter->setLiteralMode(false);
        $jigConverter->addHTML("</pre>");
    }

    /**
     * @param JigConverter $jigConverter
     * @param $segmentText
     * @throws JigException
     */
    function processSyntaxHighlighterStart(JigConverter $jigConverter, $segmentText)
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
    
        //$jigConverter->addHTML(self::SYNTAX_START);
        $jigConverter->addHTML("<!-- SyntaxHighlighter Start -->");
    
        if ($srcFile) {
            //TODO - add error checking.
            $rawLink = "/staticFile/".$srcFile;
            $jigConverter->addHTML("\n\n<pre class='brush: $lang; toolbar: true;' data-link='$rawLink'>");
            $jigConverter->setLiteralMode(true);
            $fileNameToServe = $this->sourceFileFetcher->fetch($srcFile);
    
            $fileContents = htmlentities(file_get_contents($fileNameToServe), ENT_QUOTES);
            $fileContents = str_replace("<?php ", "&lt;php", $fileContents);
            $fileContents = str_replace("? >", "?&gt;", $fileContents);
    
            $jigConverter->addHTML($fileContents);
        }
        else {
            $jigConverter->addHTML("\n\n<pre class='brush: $lang; toolbar: true;'>");
            $jigConverter->setLiteralMode(true);
        }
    }
}

