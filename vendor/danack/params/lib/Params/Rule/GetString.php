<?php

declare(strict_types=1);

namespace Params\Rule;

use Params\Rule;
use Params\ValidationResult;
use VarMap\VarMap;
use Params\OpenApi\ParamDescription;

class GetString implements Rule
{
    /** @var VarMap */
    private $variableMap;

    const ERROR_MESSAGE = 'Value not set for %s.';

    public function __construct(VarMap $variableMap)
    {
        $this->variableMap = $variableMap;
    }

    /**
     * @inheritdoc
     */
    public function __invoke(string $variableName, $_) : ValidationResult
    {
        if ($this->variableMap->has($variableName) !== true) {
            $message = sprintf(self::ERROR_MESSAGE, $variableName);
            return ValidationResult::errorResult($message);
        }

        $value = (string)$this->variableMap->get($variableName);

        return ValidationResult::valueResult($value);
    }

    public function updateParamDescription(ParamDescription $paramDescription)
    {
        $paramDescription->setType(ParamDescription::TYPE_STRING);
        $paramDescription->setRequired(true);
    }
}
