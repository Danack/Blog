<?php

namespace BaseReality\Form;

use Intahwebz\Form\Form;

class BlogEditForm extends Form {

    function getDefinition() {
        $definition = array(

            'class'         => 'blogEditForm',

            'startElements' => [
                [
                    'type'  => \Intahwebz\FormElement\Title::class,
                    'value' => 'Blog edit',
                ]
            ],

            'rowElements'   => array(
                array(
                    'title',
                    'type'       => \Intahwebz\FormElement\Text::class,
                    'label'      => 'Post title',
                    'name'       => 'title',
                    'validation' => array(
                        "Zend\\Validator\\StringLength" => array(
                            'min' => 8,
                        ),
                    )
                ),
                array(
                    'isActive',
                    'type'  => \Intahwebz\FormElement\CheckBox::class,
                    'label' => 'Is active',
                    'name'  => 'isActive',
                ),
            ),

            'endElements'   => array(
                array(
                    'submitButton',
                    'type'  => \Intahwebz\FormElement\SubmitButton::class,
                    'label' => null,
                    'text'  => 'Update',
                ),
            ),

            'validation'    => array(//form level validation.
            )
        );

        return $definition;
    }
}

