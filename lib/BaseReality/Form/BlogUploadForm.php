<?php

namespace BaseReality\Form;

use Intahwebz\Form\Form;

class BlogUploadForm extends Form
{
    public function getDefinition()
    {
        $definition = array(

            'class' => 'blogEditForm',

            'startElements' => [
                [
                    'type' => 'Intahwebz\FormElement\Title',
                    'value' => 'Blog Upload',
                ]
            ],

            'rowElements' => array(
                array(
                    'type' => 'Intahwebz\FormElement\File',
                    'label' => 'Select a file to upload',
                    'name' => 'blogFile',
                    'validation' => array(
                        'Intahwebz\Validator\FileSize' => array(
                            'minSize' => 100
                        ),
//						"Zend\\Validator\\StringLength" => array(
//							'min' => 8,
//						),
                    )
                ),
                array(
                    'isActive',
                    'type' => 'Intahwebz\FormElement\CheckBox',
                    'label' => 'Is active',
                    'name' => 'isActive',
                ),
            ),

            'endElements' => array(
//				array(
//					'type' => \Intahwebz\FormElement\Hidden::class,
//					'name' => 'formSubmitted',
//					'value' => true
//				),
                array(
                    'submitButton',
                    'type' => 'Intahwebz\FormElement\SubmitButton',
                    'label' => null,
                    'text' => 'Upload',
                ),
            ),

            'validation' => array(
                //form level validation.
            )
        );

        return $definition;
    }
}
