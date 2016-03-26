<?php


namespace Article\InterfaceSegregation\Step3;

class ArrayVariableMap implements VariableMap
{
    public function __construct(array $variables)
    {
        $this->variables = $variables;
    }

    public function getVariable(string $variableName) : string
    {
        if (array_key_exists($variableName, $this->variables) === false) {
            $message = "Parameter [$variableName] is not available";
            throw new ParamMissingException($message);
        }

        return $this->variables[$variableName];
    }
}
