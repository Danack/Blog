<?php

namespace BaseReality\Form;

use Intahwebz\Form\Form;

class Base64Form extends Form{

    function getDefinition() {
        $definition = array(
            
            'startElements' => [
                [
                    'type' => \Intahwebz\FormElement\Title::class,
                    'value' => 'Note add',
                ]
            ],

            'rowElements' => array(
            
            ),

            'endElements' => array(

                array(
                    'type' => \Intahwebz\FormElement\TextArea::class,
                    'label' => 'Base64',
                    'name' => 'base64',
                    'validation' => array(
                    ),
                    'rows' => 30,
                    //'cols' => 100,
                ),

                array(
                    'type' => \Intahwebz\FormElement\CheckBox::class,
                    'label' => 'Download',
                    'name' => 'download',
                    'validation' => array(
                    ),
                ),
                
                
                array(
                    'submitButton',
                    'type' => \Intahwebz\FormElement\SubmitButton::class,
                    'label' => null,
                    'text' => 'Decode',
                ),
                array(
                    'submitButton',
                    'type' => \Intahwebz\FormElement\SubmitButton::class,
                    'label' => null,
                    'text' => 'Encode',
                ),
            ),

            'validation' => array(
                //form level validation.
            )
        );

        return $definition;
    }
}

