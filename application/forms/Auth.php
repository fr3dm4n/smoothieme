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
        //zf configure db-adapter "adapter=Pdo_Mysql&username=dbuser&passwor123&dbname=dbsmoothieme"
        $this->addElement('password', 'password', array(
                'placeholder' => 'Passwort',
                'required' => true,
                'size' => 30            )
        );

        $this->addElement('button', 'submit', array(
                'ignore' => true,
                'label' => 'Einlogen',
                'buttonType'    => 'primary',
                'icon'          => 'ok',
                'escape' => false

            )
        );


    }


}

