<?php

namespace Blog\Bridge;

use Intahwebz\Form\DataStore;
use Intahwebz\Session;

class SessionDataStore implements DataStore
{
    /**
     * @var Session
     */
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }
    
    public function getData($name, $default, $clearOnRead)
    {
        return $this->session->getSessionVariable($name, $default, $clearOnRead);
    }

    public function storeData($name, $data)
    {
        return $this->session->setSessionVariable($name, $data);
    }
}
