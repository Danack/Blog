<?php

namespace BaseReality\Form;

use Intahwebz\Form\Form;

class SignupForm extends Form{

    function getDefinition() {
        $definition = array(

            'class' => 'signupForm',

            'rowElements' => array(

                array(
                    'title',
                    'type' =>  \Intahwebz\FormElement\Text::class,
                    'label' => 'Name',
                    'name' => 'name',
                    'validation' => array(
                        "Zend\\Validator\\StringLength" => array(
                            'min' => 2,
                        ),
                    )
                ),
                array(
                    'title',
                    'type' =>  \Intahwebz\FormElement\Text::class,
                    'label' => 'Email',
                    'name' => 'email',
                    'validation' => array(
                        "Zend\\Validator\\EmailAddress" => array(
                            'min' => 7,
                        ),
                    )
                ),

                array(
                    'isActive',
                    'type' => \Intahwebz\FormElement\CheckBox::class,
                    'label' => 'Newsletter',
                    'name' => 'newsletter',
                ),
            ),

            'endElements' => array(
                array(
                    'submitButton',
                    'type' => \Intahwebz\FormElement\SubmitButton::class,
                    'label' => null,
                    'text' => 'Update',
                ),
            ),

            'validation' => array(
                //form level validation.
            )
        );

        return $definition;
    }
}

