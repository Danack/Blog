<?php


namespace Blog\Site;

use Blog\Site\LoginStatus;
use Blog\Route;

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
        
        $output = "<div class='row panel panel-default'>";
        $output .= "<div class='col-md-12'>";
        $output .= "Logged in<br />";
        $output .= "<a href='/logout'>Logout</a ><br />";
        $output .= "<a href='".Route::showDrafts()."'>Show drafts</a><br/>";
        $output .= "<a href='".Route::showUpload()."'>Upload blag</a><br/>";
        $output .= "<a href='".Route::templateViewer()."'>Template viewer</a><br/>";

        $output .= "    </div>";
        $output .= "</div>";


        return $output;
    }
}
