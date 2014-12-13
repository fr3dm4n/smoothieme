<?php

class Application_Form_Auth extends Twitter_Bootstrap_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */

        $this->setIsArray(true);
        $this->setElementsBelongTo('bootstrap');

        $this->setMethod('post');

        $this->addElement('text', 'username', array(
                'placeholder' => 'Benutzername',
                'required' => true,
                'filters' => array('StringTrim'),
                'size' => 30
            )
        );

        $this->addElement('password', 'password', array(
                'placeholder' => 'Passwort',
                'required' => true,
                'size' => 30            )
        );

        $this->addElement('submit', 'submit', array(
                'ignore' => true,
                'label' => 'Einlogen',
                'buttonType'    => 'primary',
                'icon'          => 'ok',
                'escape' => false

            )
        );


    }


}

