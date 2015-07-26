<?php

namespace Intahwebz\Response;


class APIResponse extends SendableResponse implements Response {

    private $rawData;
    private $data = [];

    public function __construct($rawData) {
        $this->setRawData($rawData);
    }

    static public function fromRawData($rawData) {
        $instance = new self($rawData);
        //$instance->setRawData($rawData);
        return $instance;
    }

//    /**
//     * @return bool
//     */
//    function isOK() {
//        return ($this->response->getStatus() == 200);
//    }

    function setData($name, $value) {
        $this->data[$name] = $value;
    }

    function setRawData($rawData) {
        $this->rawData = $rawData;
    }
    
    function setStatus($status) {
    }
    

    function process(\Intahwebz\Request $request) {
        $this->sendHeaders([]);
        if ($this->rawData) {
            echo json_encode_object($this->rawData);
        }
        else {
            throw new \Exception("Not implemented yet.");
        }
    }
}
 