<?php

namespace Blog\Site\AuthBox;

use Blog\Site\AuthBox;

class LoginBox extends AuthBox
{
    public function render()
    {

$output = <<< HTML
Login box
HTML;

        return $output;
    }
}
