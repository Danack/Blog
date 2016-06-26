<?php

namespace Blog\Site\ThemeCSS;

use Blog\Site\ThemeCSS;
use ScriptHelper\ScriptInclude;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;

class ThemeCSSImpl implements ThemeCSS
{
    private $scriptInclude;
    private $serverRequest;
    
    public function __construct(ScriptInclude $scriptInclude, ServerRequest $serverRequest)
    {
        $this->scriptInclude = $scriptInclude;
        $this->serverRequest = $serverRequest;
    }
    
    private function isLight()
    {
        $domain = $this->serverRequest->getUri()->getHost();
        $query = $this->serverRequest->getUri()->getQuery();
        parse_str($query, $params);

        if (stripos($domain, 'bloglight') !== false ||
            array_key_exists('light', $params) === true) {
            return true;
        }

        return false;
    }
    
    
    public function renderThemeButton()
    {
        $uri = $this->serverRequest->getUri();
        
        $host = $uri->getHost();
        $firstDotPosition = strpos($host, '.');

        $text = "Go light";
        $prefix = 'bloglight';

        if ($this->isLight()) {
            $text = "Go dark";
            $prefix = 'blog';
        }

        $newHost = $prefix.substr($host, $firstDotPosition);
        $uri = $uri->withHost($newHost);

        $html = <<< HTML
<a href="$uri" class="linkToCode">
    $text
</a>
HTML;

        return $html;
    }

    public function addCSS()
    {
        if ($this->isLight()) {
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
