<?php

namespace BaseReality\Form;

use Intahwebz\Form\Form;

class Base64Form extends Form
{
    public function getDefinition()
    {
        $definition = array(
            
            'startElements' => [
                [
                    'type' => 'Intahwebz\FormElement\Title',
                    'value' => 'Note add',
                ]
            ],

            'rowElements' => array(
            
            ),

            'endElements' => array(

                array(
                    'type' => 'Intahwebz\FormElement\TextArea',
                    'label' => 'Base64',
                    'name' => 'base64',
                    'validation' => array(
                    ),
                    'rows' => 30,
                    //'cols' => 100,
                ),

                array(
                    'type' => 'Intahwebz\FormElement\CheckBox',
                    'label' => 'Download',
                    'name' => 'download',
                    'validation' => array(
                    ),
                ),
                
                
                array(
                    'submitButton',
                    'type' => 'Intahwebz\FormElement\SubmitButton',
                    'label' => null,
                    'text' => 'Decode',
                ),
                array(
                    'submitButton',
                    'type' => 'Intahwebz\FormElement\SubmitButton',
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
