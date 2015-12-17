<?php


namespace Blog;



class Debug
{
    
    private $debug = [];

//    /**
//     * @var Session
//     */
//    private $session;
    
    public function __construct(/*Session $session*/)
    {
        //$this->session = $session;
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
    
    /* Stops passwords from being put into log files.
     * Need to make it generate valid php arrays so make life easier.
     */
    function dump_table($var)
    {
        $forbiddenKeys = array(
            //'password',
        );
    
        if (is_array($var) or is_object($var)) {
            foreach ($var as $key => $value) {
                if (is_array($value) or is_object($value)) {
                    $this->dump_table($value);
                }
                else {
                    if (in_array($key, $forbiddenKeys) == true) {
                        $value = '********';
                    }
                    echo "'$key' => '$value' ";
                }
            }
        }
        else {
            echo "'$var' ";
        }
    }
    
    function getVar_DumpOutput($response)
    {
        ob_start();
        $this->dump_table($response);
        $obContents = ob_get_contents();
        ob_end_clean();
    
        return $obContents;
    }
}
