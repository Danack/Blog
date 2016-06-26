<?php




namespace Blog\Site;

interface LoginStatus
{
    public function isLoggedIn();

    public function logoutUser();
}