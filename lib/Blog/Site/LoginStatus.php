<?php


namespace Blog\Site;

use ASM\Session;
use BaseReality\Content\BaseRealityConstant;

class LoginStatus
{
    /**
     * @var Session
     */
    private $session;
    
    public function __construct(Session $session)
    {
        $this->session = $session;
    }
    
    public function isLoggedIn()
    {
        $data = $this->session->getData();
        if (array_key_exists(BaseRealityConstant::$userRole, $data)) {
            return true;
        }

        return false;
    }
    
    public function logoutUser()
    {
        $data = $this->session->getData();
        unset($data[BaseRealityConstant::$userRole]);
        $this->session->setData($data);
    }
}
