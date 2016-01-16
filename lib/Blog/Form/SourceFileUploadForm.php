<?php

namespace Blog\Form;

use FCForms\Form\Form;

function sanitize($originalFilename)
{
    $result = preg_replace('#[^\w\d\.\-]#', '', $originalFilename);
    
    if ($result === false) {
        throw new \Exception("preg_replace failed.");
    }

    return $result;
}

class SourceFileUploadForm extends Form
{
    public function getBlogUpload()
    {
        $filename =  $this->getValue('end', 'filename');
        $file =  $this->getValue('end', 'text');
        
        /** @var $file \FCForms\UploadedFile */
        $filename = sanitize($file->getOriginalName());
        $fileContents = file_get_contents($file->getFilename());

        return [$filename, $fileContents];
    }

    public function getDefinition()
    {
        $definition = array(
            'class' => 'blogEditForm',
            'startElements' => [
                [
                    'type' => 'FCForms\FormElement\Title',
                    'value' => 'Source file upload',
                ]
            ],
            'endElements' => array(
                array(
                    'type' => 'FCForms\FormElement\File',
                    'label' => 'Select a file to upload',
                    'name' => 'text',
                    'validation' => array(
                        'FCForms\Validator\FileSize' => array(
                            'minSize' => 100
                        ),
                    )
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
