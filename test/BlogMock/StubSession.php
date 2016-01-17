<?php


namespace BlogMock;

use Intahwebz\Session;

class StubEmptySession implements Session
{
    public function initSession()
    {
        // TODO: Implement initSession() method.
    }

    public function setSessionVariable($sessionName, $serializedData)
    {
        // TODO: Implement setSessionVariable() method.
    }

    public function startSession()
    {
        // TODO: Implement startSession() method.
    }

    /**
     * @param $name
     * @param mixed $default
     * @param bool $clear
     * @return mixed
     */
    public function getSessionVariable($name, $default = false, $clear = false)
    {
        // TODO: Implement getSessionVariable() method.
    }

    public function unsetSessionVariable($sessionName)
    {
        // TODO: Implement unsetSessionVariable() method.
    }

    public function regenerateID()
    {
        // TODO: Implement regenerateID() method.
    }

    public function logoutUser()
    {
        // TODO: Implement logoutUser() method.
    }
}
