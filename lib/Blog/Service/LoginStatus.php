<?php


namespace Blog\Service;

use Intahwebz\Session;
use BaseReality\Security\Role;

class LoginStatus
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function isLoggedIn()
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
