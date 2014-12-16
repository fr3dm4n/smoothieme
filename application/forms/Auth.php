<?php

class Application_Form_Auth extends Twitter_Bootstrap_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */

        $this->setName("login");
        $this->setIsArray(true);
        $this->setAction("/auth");


        $this->setMethod('post');

        $this->addElement('text', 'username', array(
                'placeholder' => 'Benutzername',
                'required' => true,
                'class'=> 'form-control',
                'filters' => array('StringTrim','StringToLower'),
                'validators' => array(array('Stringlength',false,array(0,50))),
                'size' => 30
            )
        );

        $this->addElement('password', 'password', array(
                'placeholder' => 'Passwort',
                'filters' => array('StringTrim'),
                'class'=> 'form-control',
                'validators' => array(
                    array('StringLength', false, array(0, 50)),
                ),
                'required' => true,
                'size' => 30            )
        );

        $this->addElement('submit', 'login', array(
                'ignore' => true,
                'label' => 'Anmelden',
                'buttonType'    => 'primary',
                'icon'          => 'ok',
                'escape' => false

            )
        );


    }


}

