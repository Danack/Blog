<?php

namespace Blog\Site\AuthBox;

use Blog\Site\AuthBox;

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
        $output = <<< HTML

Logged in.
HTML;

        return $output;
    }
}
