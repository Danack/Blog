<?php


namespace Blog\User;

use Blog\UserPermissions;

class AnonymousPermissions implements UserPermissions
{
    public function isLoggedIn()
    {
        return false;
    }
    
    public function getRole()
    {
        return null;
    }
}
