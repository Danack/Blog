<?php

namespace Blog\Repository;

interface LoginRepo
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
    public function createUserLogin($username, $password);
}
