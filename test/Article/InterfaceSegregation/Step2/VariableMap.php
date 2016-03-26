<?php


namespace Article\InterfaceSegregation\Step2;

interface VariableMap
{
    /**
     * @throws ParamsMissingException
     */
    public function getVariable(string $variableName) : string;
}
