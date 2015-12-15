<?php

namespace Blog\Mapper\Stub;

use Blog\Repository\LoginRepo;

class LoginStubRepo implements LoginRepo
{
    /**
     * @param $username
     * @param $password
     * @return bool
     */
    public function isLoginValid($username, $password)
    {
        return true;
    }

    /**
     * @param $username
     * @param $password
     * @return mixed
     * @throws \Exception
     */
    public function createUserLogin($username, $password)
    {
    }
}
