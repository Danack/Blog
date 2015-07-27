<?php

namespace BaseReality\Form;

use Intahwebz\Form\Form;

class BlogEditForm extends Form
{
    public function getDefinition()
    {
        $definition = array(

            'class'         => 'blogEditForm',

            'startElements' => [
                [
                    'type'  => 'Intahwebz\FormElement\Title',
                    'value' => 'Blog edit',
                ]
            ],

            'rowElements'   => array(
                array(
                    'title',
                    'type'       => 'Intahwebz\FormElement\Text',
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
                    'type'  => 'Intahwebz\FormElement\CheckBox',
                    'label' => 'Is active',
                    'name'  => 'isActive',
                ),
            ),

            'endElements'   => array(
                array(
                    'submitButton',
                    'type'  => 'Intahwebz\FormElement\SubmitButton',
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
