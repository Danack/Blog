<?php


namespace Blog\Site;

use Blog\Site\AuthBox\LoginBox;
use Blog\Site\AuthBox\LogoutBox;
use Blog\Site\LoginStatus;

//abstract class AuthBox
//{
//    abstract public function render();
//    
//    public static function createAuthBox(LoginStatus $loginStatus)
//    {
//        if ($loginStatus->isLoggedIn()) {
//            return LogoutBox::createFromUsername("Logged in");
//        }
//
//        return new LoginBox();
//    }
//}
