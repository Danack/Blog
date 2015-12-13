<?php


namespace Blog\Site;

use Blog\Site\LoginStatus;
use Blog\Routes;

class AdminLinks
{
    private $loginStatus;

    function __construct(LoginStatus $loginStatus)
    {
        $this->loginStatus = $loginStatus;
    }

    public function render()
    {
        if (!$this->loginStatus->isLoggedIn()) {
            return '';
        }

        $output = "Logged in:<br />";
        $output .= "<a href='/logout'>Logout</a ><br />";
        $output .= "<a href='".Routes::showDrafts()."'>Show drafts</a><br/>";
        $output .= "<a href='".Routes::showUpload()."'>Upload blag</a><br/>";

        return $output;
    }
}
