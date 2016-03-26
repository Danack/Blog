<?php


namespace Article\InterfaceSegregation\Step3;

interface VariableMap
{
    /**
     * @throws ParamsMissingException
     */
    public function getVariable(string $variableName) : string;
}
