<?php

class Application_Form_User extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');

        // surname
        $this->addElement('text', 'surname', array(
            'required'   => true,
            'class' => 'form-control',
            'placeholder'=> 'Vorname',
            'filters'    => array(
                'StringTrim',
                'StripTags'
            ),
            'validators' => array(
                array(
                    'StringLength',
                    false,
                    [3, 70]
                ),
            ),
        ));

        // lastname
        $this->addElement('text', 'lastname', array(
            'required'   => true,
            'class' => 'form-control',
            'placeholder'=> 'Nachname',
            'filters'    => array(
                'StringTrim',
                'StripTags'
            ),
            'validators' => array(
                array(
                    'StringLength',
                    false,
                    [3,50]
                )
            ),
        ));

        // email
        $this->addElement('text', 'email', array(
            'required'   => true,
            'class' => 'form-control',
            'placeholder'=> 'E-Mail Adresse',
            'filters'    => array('StringTrim'),
            'validators' => array(
                'emailAddress',
                array(
                    'massage' => 'Bitte gültige E-Mail eingeben'
                )
            )
        ));

        // telephone
        $this->addElement('text', 'telephone', array(
            'required'   => true,
            'class' => 'form-control',
            'placeholder'=> 'Telefonnummer',
            'filters'    => array('StringTrim'),
            'validators' => array(
                array(
                    'validator' => "Regex",
                    'options'=>array(
                        'pattern' =>'/^[0-9]+$/',
                        'messages' =>'Nur Ziffern eingeben'
                    )
                ),
            ),
        ));

        // gender
        $this->addElement('radio', 'gender', array(
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'gender',
            ),
            'MultiOptions' => array(
                'male'   => ' männlich     ',
                'female' => ' weiblich'
            ),
            'Separator'  => ' '
        ));
    }
}

