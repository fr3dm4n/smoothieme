<?php

class Application_Form_Register extends Twitter_Form
{
    public function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');

        // surname
        $this->addElement('text', 'surname', array(
            'label'      => '* Vorname:',
            'required'   => true,
            'placeholder'=> 'Vorname eingeben',
            'filters'    => array('StringTrim'),
            'validators' => array(
                'surname',
            )
        ));

        // lastname
        $this->addElement('text', 'lastname', array(
            'label'      => '* Nachname:',
            'required'   => true,
            'placeholder'=> 'Nachname eingeben',
            'filters'    => array('StringTrim'),
            'validators' => array(
                'lastname',
            )
        ));

        // Email
        $this->addElement('text', 'email', array(
            'label'      => '* E-Mail:',
            'required'   => true,
            'placeholder'=> 'E-Mail Adresse eingeben',
            'filters'    => array('StringTrim'),
            'validators' => array(
                'email',
            )
        ));

        // gender
        $this->addElement('radio', 'gender', array(
            'label'      => 'Geschlecht:',
            'required'   => false,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'gender',
            ),
            'MultiOptions' => array(
                'male' => 'männlich',
                'female' => 'weiblich'
            ),
            'Separator' => ' '
        ));

        // birthdate
        $this->addElement('select', 'birthdate', array(
            'label'      => '* Geburtsdatum:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'birthdate',
            ),
            'MultiOptions' => array(
                'US' => 'United States',
                'UK' => 'United Kingdom'
            )
        ));

        // password
        $this->addElement('password', 'password', array(
            'label'      => '* Passwort:',
            'required'   => true,
            'placeholder'=> 'Passwort eingeben',
            'filters'    => array('StringTrim'),
            'validators' => array(
                'password',
            )
        ));

        // password #2
        $this->addElement('password', 'password2', array(
            'label'      => '* Passwort:',
            'required'   => true,
            'placeholder'=> 'Passwort wiederholen',
            'filters'    => array('StringTrim'),
            'validators' => array(
                'password2',
            )
        ));

        // Add a captcha
        $this->addElement('captcha', 'captcha', array(
            'label'      => 'Bitte die fünf angezeigten Buchstaben unten eingeben:',
            'required'   => true,
            'captcha'    => array(
                'captcha' => 'Figlet',
                'wordLen' => 5,
                'timeout' => 300
            )
        ));

        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Registrieren',
        ));

        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
    }
}

/**
 * <form action="page.php" method="post">
 *  <input type="text" name="bla" />
 * </form>
 */