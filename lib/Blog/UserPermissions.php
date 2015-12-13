<?php

namespace Blog;

interface UserPermissions
{
    public function isLoggedIn();

    public function getRole();
}