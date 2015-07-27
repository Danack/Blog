<?php


namespace Intahwebz\Validator;

use Zend\Validator\AbstractValidator;

class URL extends AbstractValidator
{
    const INVALID_URL = "URL is invalid";

    protected $messageTemplates = array(
        self::INVALID_URL           => "URL is invalid.",
    );

    public function isValid($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL) == false) {
            $this->error(self::INVALID_URL);
            return false;
        }
        
        return true;
    }
}
