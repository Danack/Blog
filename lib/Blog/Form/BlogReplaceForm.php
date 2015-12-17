<?php

namespace Blog\Form;

use FCForms\Form\Form;

class BlogReplaceForm extends Form
{
    public function getDefinition()
    {
        $definition = array(
            'class'         => 'blogEditForm',
            'startElements' => [
                [
                    'type'  => 'FCForms\FormElement\Title',
                    'value' => 'Blog replace',
                ]
            ],
            'rowElements'   => array(
            ),
            'endElements'   => array(
                 array(
                    'type'  => 'FCForms\FormElement\File',
                    'label' => 'Select a file to upload',
                    'name'  => 'blogFile',
                    'validation' => array(
                        'FCForms\Validator\FileSize' => array(
                            'minSize' => 100
                        ),
                    )
                ),
                array(
                    'submitButton',
                    'type'  => 'FCForms\FormElement\SubmitButton',
                    'name' => 'submit',
                    'label' => null,
                    'text'  => 'Replace',
                ),
            ),
        );

        return $definition;
    }
}
