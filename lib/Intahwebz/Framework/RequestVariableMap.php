<?php


namespace Intahwebz\Framework;

use Intahwebz\Request;

class RequestVariableMap implements VariableMap {
    function __construct(Request $request) {
        $this->request = $request;
    }

    function getVariable($variableName, $default = false) {
        return $this->request->getVariable($variableName, $default);
    }
}

