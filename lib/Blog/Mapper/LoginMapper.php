<?php

namespace Blog\Mapper;

interface LoginMapper
{

    /**
     * @param $username
     * @param $password
     * @return bool
     */
    public function isLoginValid($username, $password);

    /**
     * @param $username
     * @param $password
     * @return mixed
     * @throws \Exception
     */
    function createUserLogin($username, $password);
}