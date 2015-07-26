<?php

namespace BaseReality\Form;

use Intahwebz\Form\Form;

class NoteEditForm extends Form{

    function addNote(\BaseReality\Content\Note $note) {
        $data = convertObjectToArray($note);
        $this->addRowValues($note->getID(), $data);
    }
    
    function getDefinition() {
        $definition = array(
            
            'startElements' => [
                [
                    'type' => \Intahwebz\FormElement\Title::class,
                    'value' => 'Note add',
                ]
            ],

            'rowElements' => array(
                array(
                    'type' => \Intahwebz\FormElement\Text::class,
                    'label' => 'Title',
                    'name' => 'title',
                    'validation' => array(
                        "Zend\\Validator\\StringLength" => array(
                            'min' => 8,
                        ),
                    )
                ),
                array(
                    'type' => \Intahwebz\FormElement\TextArea::class,
                    'label' => 'Text',
                    'name' => 'text',
                    'validation' => array(
                        \Zend\Validator\StringLength::class => array(
                            'min' => 10,
                        ),
                    ),
                    'rows' => 40,
                    //'cols' => 100,
                    //style='min-width: 480px; min-height: 480px'
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

