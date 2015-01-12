<?php

class Application_Form_Address extends Twitter_Bootstrap_Form
{
    /**
     * Konst.
     * @param null $options
     */
    public function __construct($options = null) {
        parent::__construct($options);
    }

    public function init()
        {
            $this->setName("addresses")->setMethod("post");

            $this->addElement('text', 'street', array(
                'label' => "Straße",
                'required' => true,
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
            $this->addElement('text', 'number', array(
                'label' => "Hausnummer",
                'required' => true,
                'validators' => array(
                    array(
                        'StringLength',
                        false,
                        [1, 4]
                    ),
                ),
            ));

            $this->addElement('text', 'zip', array(
                'label' => "Postleitzahl",
                'required' => true,
                'validators' => array(
                    array(
                        'StringLength',
                        false,
                        [4, 5]
                    ),
                   array(
                        'validator' => "Regex",
                        'options'=>array(
                            'pattern' =>'/^[0-9]+$/',
                            'messages' =>'Nur ganzzahlige Werte aus Ziffern'
                        )
                    ),
                ),
            ));
            $this->addElement('text', 'country', array(
                'label' => "Wohnort",
                'required' => true,
                'filters'    => array(
                    'StringTrim',
                    'StripTags'
                ),
                'validators' => array(
                    array(
                        'StringLength',
                        false,
                        [3, 70]
                    )
                ),
            ));

            //Wird später den Token bzw. zur Datei enthalten
            $this->addElement("hidden","token",array(
                'validators' => array(
                    array(
                        'StringLength',
                        false,
                        32
                    ),
                    array(
                        'Alnum',
                        false
                    ),
                ),

                'required'   => false,
            ));

            $this->addElement('submit', 'submit', array(
                'buttonType' => Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
                'label' => 'speichern',
            ));
        }
    }

