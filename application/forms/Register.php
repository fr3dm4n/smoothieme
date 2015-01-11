<?php

class Application_Form_Register extends Zend_Form
{

    public function init()
    {
        // Set the method for the display form to POST
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
                    [3,50]
                )
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
                'email',
            )
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

        // birthdate
//        $this->addElement('select', 'birthdate', array(
//            'required'   => true,
//            'class' => 'form-control',
//            'filters'    => array('StringTrim'),
//            'validators' => array(
//                'birthdate',
//            ),
//            'MultiOptions' => array(
//                'a' => '01.01.2000',
//                'b' => '31.12.2999'
//            )
//        ));

        // password
        $this->addElement('password', 'password', array(
            'required'   => true,
            'class' => 'form-control',
            'placeholder'=> 'Passwort',
            'filters'    => array('StringTrim'),
            'validators' => array(
                'password',
            )
        ));

        // password #2
        $this->addElement('password', 'password2', array(
            'required'   => true,
            'class' => 'form-control',
            'placeholder'=> 'Passwort wiederholen',
            'filters'    => array('StringTrim'),
            'validators' => array(
                'password2',
            )
        ));

        // Add a captcha
        $this->addElement('captcha', 'captcha', array(
            'required'   => true,
            'class' => 'form-control',
            'placeholder' => 'captcha',
            'captcha'    => array(
                'captcha' => 'Figlet',
                'wordLen' => 5,
                'timeout' => 300
            )
        ));

        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'buttonType' => Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
            'label'      => 'Registrieren',
        ));

        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
    }
}

