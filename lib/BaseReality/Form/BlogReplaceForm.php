<?php

namespace BaseReality\Form;

use Intahwebz\Form\Form;

class BlogReplaceForm extends Form
{
    public function getDefinition()
    {
        $definition = array(

            'class'         => 'blogEditForm',

            'startElements' => [
                [
                    'type'  => 'Intahwebz\FormElement\Title',
                    'value' => 'Blog replace',
                ]
            ],

            'rowElements'   => array(
                array(
                    'type'  => 'Intahwebz\FormElement\File',
                    'label' => 'Select a file to upload',
                    'name'  => 'blogFile',
                    'validation' => array(
                        'Intahwebz\Validator\FileSize' => array(
                            'minSize' => 100
                        ),
                    )
                ),
            ),

            'endElements'   => array(
                array(
                    'submitButton',
                    'type'  => 'Intahwebz\FormElement\SubmitButton',
                    'label' => null,
                    'text'  => 'Replace',
                ),
            ),

            'validation'    => array(
                //form level validation.
            )
        );

        return $definition;
    }
}
