<?php

class Application_Form_Register extends Zend_Form
{

    public function init()
    {
        $this->setName("register");
        $this->setIsArray(true);
        $this->setAction("/register");

        $this->setMethod('post');

        // Benutzername
        $this->addElement('text', 'username', array(
            'required' => true,
            'class' => 'form-control',
            'placeholder' => 'Benutzername',
            'filters' => array('StringTrim'),
            'validators' => array(
                array(
                    'StringLength',
                    false,
                    [3, 70]
                ),
            )
        ));


        // surname
        $this->addElement('text', 'surname', array(
            'required' => true,
            'class' => 'form-control',
            'placeholder' => 'Vorname',
            'filters' => array(
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
            'required' => true,
            'class' => 'form-control',
            'placeholder' => 'Nachname',
            'filters' => array(
                'StringTrim',
                'StripTags'
            ),
            'validators' => array(
                array(
                    'StringLength',
                    false,
                    [3, 50]
                )
            ),
        ));

        // email
        $this->addElement('text', 'email', array(
            'required' => true,
            'class' => 'form-control',
            'placeholder' => 'E-Mail Adresse',
            'filters' => array('StringTrim'),
            'validators' => array(
                'emailAddress',

            )
        ));

        // telephone
        $this->addElement('text', 'telephone', array(
            'required' => true,
            'class' => 'form-control',
            'placeholder' => 'Telefonnummer',
            'filters' => array('StringTrim'),
            'validators' => array(
                array(
                    'validator' => "Regex",
                    'options' => array(
                        'pattern' => '/^[0-9\+]{9,15}$/',
                        'messages' => 'Nur Ziffern eingeben'
                    )
                ),
            ),
        ));

        // gender
        $this->addElement('radio', 'gender', array(
            'required' => true,
            'filters' => array('StringTrim'),

            'MultiOptions' => array(
                'male' => ' männlich     ',
                'female' => ' weiblich'
            ),
            'Separator' => ' '
        ));
    }
}