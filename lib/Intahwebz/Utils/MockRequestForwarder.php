<?php



namespace Intahwebz\Utils;



class MockRequestForwarder extends RequestFowarder {

    private $message;
    private $route;
    private $params;

    public function __construct() {
        //yes, we have no dependencies.
    }


    function forward($message, $route, $params = array()) {
        $this->message = $message;
        $this->route = $route;
        $this->params = $params;
    }

    /**
     * @return mixed
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * @return mixed
     */
    public function getRoute() {
        return $this->route;
    }

}
 