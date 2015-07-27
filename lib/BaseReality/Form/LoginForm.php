<?php

namespace BaseReality\Form;

use Intahwebz\Form\Form;

class LoginForm extends Form
{
    public function getDefinition()
    {
        $definition = array(

            'requireHTTPS' => true,

            'startElements' => [
                [
                    'type' => 'Intahwebz\FormElement\Title',
                    'value' => 'Login',
                ]
            ],

            'rowElements' => array(
                
            ),

            'endElements' => array(
                array(
                    'type' => 'Intahwebz\FormElement\Hidden',
                    'name' => 'returnURL',
                    'validation' => array(
                    )
                ),
                array(
                    'type' => 'Intahwebz\FormElement\Text',
                    'label' => 'Username',
                    'name' => 'username',
                    'validation' => array(
                        "Zend\\Validator\\StringLength" => array(
                            'min' => 4,
                        ),
                    )
                ),
                array(
                    'type' => 'Intahwebz\FormElement\Password',
                    'label' => 'Password',
                    'name' => 'password',
                    'validation' => array(
                        "Zend\\Validator\\StringLength" => array(
                            'min' => 4,
                        ),
                    )
                ),
                array(
                    'submitButton',
                    'type' => 'Intahwebz\FormElement\SubmitButton',
                    'label' => null,
                    'text' => 'Login',
                ),
            ),
    
            'validation' => array(
                //form level validation.
            )
        );

        return $definition;
    }


    public function serialize()
    {
        return parent::serialize();
    }
}
