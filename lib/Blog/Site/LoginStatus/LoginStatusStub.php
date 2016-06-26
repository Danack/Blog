<?php


namespace Blog\Site\LoginStatus;

use Blog\Site\LoginStatus;

class LoginStatusStub implements LoginStatus
{
    public function isLoggedIn()
    {
        return false;
    }

    public function logoutUser()
    {
    }
}
