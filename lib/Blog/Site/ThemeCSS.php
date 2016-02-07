<?php


namespace Blog\Site;

use ScriptHelper\ScriptInclude;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;

class ThemeCSS
{
    private $scriptInclude;
    private $serverRequest;
    
    public function __construct(ScriptInclude $scriptInclude, ServerRequest $serverRequest)
    {
        $this->scriptInclude = $scriptInclude;
        $this->serverRequest = $serverRequest;
    }

    public function addCSS()
    {
        $domain = $this->serverRequest->getUri()->getHost();
        $query = $this->serverRequest->getUri()->getQuery();
        parse_str($query, $params);

        if (stripos($domain, 'bloglight') !== false ||
            array_key_exists('light', $params) === true) {
            $this->scriptInclude->addCSSFile("bootstrap_light");
            $this->scriptInclude->addCSSFile("bootswatch_light");
            $this->scriptInclude->addCSSFile("code_highlight_light");
        }
        else {
            $this->scriptInclude->addCSSFile("bootstrap");
            $this->scriptInclude->addCSSFile("bootswatch");
            $this->scriptInclude->addCSSFile("code_highlight_dark");

        }
    }
}
