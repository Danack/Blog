<?php

namespace BaseReality\Form;

use Intahwebz\Form\Form;

class QuoteEditForm extends Form {

    function getDefinition() {
        $definition = array(

            'startElements' => [
                [
                    'type' => \Intahwebz\FormElement\Title::class,
                    'value' => 'Quote edit',
                ]
            ],

            'rowElements' => array(
                array(
                    'type' => \Intahwebz\FormElement\TextArea::class,
                    'label' => 'Text',
                    'name' => 'text',
                    'validation' => array(
                        "Zend\\Validator\\StringLength" => array(
                            'min' => 8,
                        ),
                    )
                ),
                array(
                    'type' => \Intahwebz\FormElement\Text::class,
                    'label' => 'Author',
                    'name' => 'author',
                    'validation' => array(
                        \Intahwebz\Validator\StringLengthOrNull::class => array(
                            'min' => 2,
                        ),
                    )
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

