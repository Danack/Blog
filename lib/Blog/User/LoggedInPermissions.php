<?php

namespace Blog\User;


use Blog\UserPermissions;

class LoggedInPermissions implements UserPermissions
{
    private $role;

    public function __construct($role)
    {
        $this->role = $role;
    }
    
    public function isLoggedIn()
    {
        return true;
    }
    
    public function getRole()
    {
        $this->role;
    }
}
