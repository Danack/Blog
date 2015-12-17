<?php

namespace Blog\Form;

use FCForms\Form\Form;

class BlogUploadForm extends Form
{
    public function getBlogUpload()
    {
        $title =  $this->getValue('end', 'title');
        $file =  $this->getValue('end', 'blogFile');
        $isActive = $this->getValue('end', 'isActive');
        $fileContents = file_get_contents($file->getFilename());

        return [$title, $fileContents, $isActive];
    }

    public function getDefinition()
    {
        $definition = array(
            'class' => 'blogEditForm',
            'startElements' => [
                [
                    'type' => 'FCForms\FormElement\Title',
                    'value' => 'Blog Upload',
                ]
            ],
            'endElements' => array(
                array(
                    'type' => 'FCForms\FormElement\Text',
                    'label' => 'Title',
                    'name' => 'title',
                    'validation' => array(
                        "Zend\\Validator\\StringLength" => array(
                            'min' => 4,
                        ),
                    )
                ),
                array(
                    'type' => 'FCForms\FormElement\File',
                    'label' => 'Select a file to upload',
                    'name' => 'blogFile',
                    'validation' => array(
                        'FCForms\Validator\FileSize' => array(
                            'minSize' => 100
                        ),
                    )
                ),
                array(
                    'isActive',
                    'type' => '\FCForms\FormElement\CheckBox',
                    'label' => 'Is active',
                    'name' => 'isActive',
                ),
                array(
                    'submitButton',
                    'type' => 'FCForms\FormElement\SubmitButton',
                    'name' => 'submit',
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
