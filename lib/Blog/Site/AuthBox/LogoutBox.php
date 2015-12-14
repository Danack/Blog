<?php

namespace Blog\Site\AuthBox;

use Blog\Site\AuthBox;
use Blog\Routes;

class LogoutBox extends AuthBox
{
    private $username;
    
    // Prevent accidental construction
    private function __construct()
    {
    }

    public static function createFromUsername($username)
    {
        $instance = new static;
        $instance->username = $username;
        
        return $instance;
    }

    public function render()
    {
        $html = <<< HTML
Logged in.<br/>
<a href='/logout'>Logout</a ><br />
<a href='%s'>Show drafts</a><br/>
<a href='%s'>Upload blag</a><br/>
HTML;
        
        $output = sprintf( 
            $html,
            Routes::showDrafts(),
            Routes::showUpload()
        );

        return $output;
    }
}
