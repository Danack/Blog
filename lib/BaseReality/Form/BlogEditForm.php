<?php

namespace BaseReality\Form;

use Blog\Content\BlogPost;
use FCForms\Form\Form;

class BlogEditForm extends Form
{
    public function initFromBlogPost(BlogPost $blogPost)
    {
        $data = array(
            'blogPostID' => $blogPost->blogPostID,
            'title' => $blogPost->title,
            'isActive' => $blogPost->isActive,
        );

        $this->initFromData($data);
    }

    public function getDefinition()
    {
        $definition = array(
            'class'         => 'blogEditForm',
            'startElements' => [
                [
                    'type' => 'FCForms\FormElement\Title',
                    'value' => 'Blog edit',
                ]
            ],
            'endElements'   => array(
                array(
                    'title',
                    'type' => 'FCForms\FormElement\Text',
                    'label'      => 'Post title',
                    'name'       => 'title',
                    'validation' => array(
                        "Zend\\Validator\\StringLength" => array(
                            'min' => 8,
                        ),
                    )
                ),
                array(
                    'type' => 'FCForms\FormElement\Hidden',
                    'name'  => 'blogPostID',
                ),
                array(
                    'type' => 'FCForms\FormElement\CheckBox',
                    'label' => 'Is active',
                    'name'  => 'isActive',
                ),
                
                array(
                    'submitButton',
                    'type' => 'FCForms\FormElement\SubmitButton',
                    'name' => 'submit',
                    'text'  => 'Update',
                ),
            ),
            'validation'    => array(
                //form level validation.
            )
        );

        return $definition;
    }
}
