<?php

namespace BaseReality\Form;

use Intahwebz\Form\Form;


class ImageEditForm extends Form{

    function getDefinition() {
        $definition = array(

            'class' => 'standardForm',

            'rowElements' => array(
                array(
                    'title',
                    'type' => \Intahwebz\FormElement\Label::class,
                    'name' => 'imageID',
                ),

                array(
                    'title',
                    'type' => \Intahwebz\FormElement\ImageLabel::class,
                    'name' => 'imageURL',
                ),

                array(
                    'title',
                    'type' => \Intahwebz\FormElement\Text::class,
                    'label' => 'Name',
                    'name' => 'name',
                    'validation' => array(
                        \Zend\Validator\StringLength::class => array(
                            'min' => 8,
                        ),
                    )
                ),

                array(
                    'delete',
                    'type' => \Intahwebz\FormElement\Link::class
                ),
            ),

            'endElements' => array(
                array(
                    'submitButton',
                    'type' => \Intahwebz\FormElement\SubmitButton::class,
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

