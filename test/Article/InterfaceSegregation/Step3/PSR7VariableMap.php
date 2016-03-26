<?php


namespace Article\InterfaceSegregation\Step3;

use Psr\Http\Message\ServerRequestInterface;

class PSR7VariableMap implements VariableMap
{
    private $serverRequest;

    public function __construct(ServerRequestInterface $serverRequest)
    {
        $this->serverRequest = $serverRequest;
    }

    public function getVariable(string $variableName) : string
    {
        $queryParams = $this->serverRequest->getQueryParams();
        if (array_key_exists($variableName, $queryParams) === false) {
            $message = "Parameter [$variableName] is not available";
            throw new ParamMissingException($message);
        }

        return $queryParams[$variableName];
    }
}
