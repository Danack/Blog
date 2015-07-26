<?php


namespace Blog\Model;


class TemplateHTML {
    
    private $html;

    public function __construct($html)
    {
        $this->html = htmlentities($html, ENT_DISALLOWED | ENT_HTML401 | ENT_NOQUOTES, 'UTF-8');
    }

    function render()
    {
        echo $this->html;
    }
}

