<?php


namespace BlogStub;

use Intahwebz\Framework\VariableMap;

class StubVariableMap implements VariableMap {

    /**
     * @var array
     */
    private $data;
    
    function __construct($data = [])
    {
        $this->data = $data;
    }

    function getVariable($variableName, $default = false)
    {
        if (array_key_exists($variableName, $this->data)) {
            return $this->data[$variableName];
        }
        return $default;
    }
}

