<?php


namespace ImagickDemo\Framework;


class ArrayVariableMap implements VariableMap {

    function __construct(array $variables) {
        $this->variables = $variables;
    }

    function getVariable($variableName, $default = false) {
        if(array_key_exists($variableName, $this->variables) == true){
            $result = $this->variables[$variableName];
        }
        else{
            $result = $default;
        }


        return $result;
    }
}

