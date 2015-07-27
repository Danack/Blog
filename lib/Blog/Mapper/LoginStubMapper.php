<?php

namespace Blog\Mapper;

class LoginStubMapper implements LoginMapper
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
