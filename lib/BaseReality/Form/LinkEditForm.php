<?php

namespace BaseReality\Form;

use Intahwebz\Form\Form;

class LinkEditForm extends Form{

    function getDefinition() {
        $definition = array(
            
            'startElements' => [
                [
                    'type' => 'Intahwebz\FormElement\Title',
                    'value' => 'Link add',
                ]
            ],
            'rowElements' => array(
                array(
                    'type' => 'Intahwebz\FormElement\Text',
                    'label' => 'Description',
                    'name' => 'description',
                    'validation' => array(
                        "Zend\\Validator\\StringLength" => array(
                            'min' => 8,
                        ),
                    )
                ),
                array(
                    'type' => 'Intahwebz\FormElement\Text',
                    'label' => 'URL',
                    'name' => 'url',
                    'validation' => array(
                        'Intahwebz\Validator\URL' => array(),
                    ),
                ),
            ),

            'endElements' => array(
                array(
                    'submitButton',
                    'type' => 'Intahwebz\FormElement\SubmitButton',
                    'label' => null,
                    'text' => 'Add link',
                ),
            ),

            'validation' => array(
                //form level validation.
            )
        );

        return $definition;
    }
}

