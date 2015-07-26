<?php


namespace Blog\Service;

use Intahwebz\Session;
use BaseReality\Security\Role;


class LoginStatus {
    
    private $session;
    
    function __construct(Session $session)
    {
        $this->session = $session;
    }

    function isLoggedIn()
    {
        $userRole = $this->session->getSessionVariable(
            \BaseReality\Content\BaseRealityConstant::$userRole
        );
    
        if ($userRole == Role::ADMIN) {
            return true;
        }

        return false;
    }
}

