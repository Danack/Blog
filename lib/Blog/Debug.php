<?php


namespace Blog;

use ASM\Session;

class Debug
{
    
    private $debug = [];

    /**
     * @var Session
     */
    private $session;
    
    public function __construct(Session $session)
    {
        $this->session = $session;
    }
    
    
    public function add($string)
    {
        $this->debug[] .= $string;
    }
    
    public function render()
    {
        $output = implode("<br/>", $this->debug);

//        $output .= "Role is: ".$this->session->getSessionVariable(
//            \BaseReality\Content\BaseRealityConstant::$userRole, 
//            'notset'
//        );

        return $output;
    }
}
