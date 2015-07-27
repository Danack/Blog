<?php

namespace BaseReality\Form;

use Intahwebz\Form\Form;

class SiteConfigEditForm extends Form
{
    public function getDefinition()
    {
        $definition = array(
            'class' => 'blogEditForm',
            
            'startElements' => [
                [
                    'type' => 'Intahwebz\FormElement\Title',
                    'value' => 'Site config',
                ]
            ],

            'rowElements' => array(
            ),

            'endElements' => array(
                array(
                    'type' => 'Intahwebz\FormElement\Text',
                    'label' => 'Images per page',
                    'name' => 'imagesPerPage',
                    'validation' => [
                        new \Zend\Validator\Between(['min' => 0, 'max' => 1000,
                        ]),
                    ],
                    'filter' => [
                        new \Zend\Filter\Int(),
                    ]
                ),

                array(
                    'type' => 'Intahwebz\FormElement\CheckBox',
                    'label' => 'X_ACCEL_REDIRECT',
                    'name' => 'X_ACCEL_REDIRECT',
                    'validation' => [],
                    'filter' => [
                        new \Zend\Filter\Boolean(),
                    ]
                ),

                array(
                    'type' => 'Intahwebz\FormElement\CheckBox',
                    'label' => 'Pack JS + CSS scripts',
                    'name' => 'packScripts',
                    'validation' => [],
                    'filter' => [
                        new \Zend\Filter\Boolean(),
                    ]
                ),


                array(
                    'type' => 'Intahwebz\FormElement\Text',
                    'label' => 'Thumbnail size',
                    'name' => 'THUMBNAIL_SIZE',
                    'validation' => [
                        new \Zend\Validator\Between(['min' => 32, 'max' => 256,]),
                    ],
                    'filter' => [
                        new \Zend\Filter\Int(),
                    ]
                ),


//                array(
//                    'type' => \Intahwebz\FormElement\Select::class,
//                    'label' => 'Recompile templates',
//                    'name' => 'templateRecompile',
//                    'options' => [
//                        'Never' => 'Never',
//                        'Modified' => 'Modified',
//                        'Always' => 'Always',
//                    ],
//                    'validation'
//                    
//                ),
//
//                array(
//                    'type' => \Intahwebz\FormElement\Select::class,
//                    'label' => 'Recompile JS/CSS',
//                    'name' => 'scriptRecompile',
//                    'options' => [
//                        'Never' => 'Never',
//                        'Modified' => 'Modified',
//                        'Always' => 'Always',
//                    ]
//                ),

//				array(
//					'type' => \Intahwebz\FormElement\Hidden::class,
//					'name' => 'formSubmitted',
//					'value' => true
//				),
                array(
                    'submitButton',
                    'type' => 'Intahwebz\FormElement\SubmitButton',
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
