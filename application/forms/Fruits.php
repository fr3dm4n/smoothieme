<?php

class Application_Form_Fruits extends Twitter_Bootstrap_Form {
    /**
     * Konst.
     * @param null $options
     */
    public function __construct($options = null) {
        parent::__construct($options);
    }

    /**
     * Initialisiert Fruit-FOrm elemente
     * @throws Zend_Form_Exception
     */
    public function init() {
        $this->setName("fruits")->setMethod("post");

        $this->addElement("text", "name", array(
            'label'      => "Name",
            'required'   => true,
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

        $this->addElement("text", "color", array(
            'label'      => 'Farbe',
            'filters'    => array(
                'StringTrim',
                'StripTags'
            ),
            'validators' => array(
                array(
                    'StringLength',
                    false,
                    [4, 7]
                ),
                array(
                    'regex',
                    false,
                    array(
                        'pattern'  => '/#[A-Fa-f0-9]{3,6}/i',
                        'messages' => 'Muss ein Hex-Farbe im Format #333000 sein'
                    )
                )
            ),
            'value'=>"#10ae00", // grün
            'required'   => true,
            'size'       => 7,
        ));


        $this->addElement("text", "price", array(
            'label'      => 'Preis',
            'filters'    => array(
                'StringTrim',
                'StripTags',
                array('PregReplace',
                      array('match' => '/,/',
                            'replace' => '.')
                )
            ),
            'validators' => array(
                array(
                    'StringLength',
                    false,
                    [1, 14]
                ),
                array(
                    'validator' => "Regex",
                    'options'=>array(
                        'pattern' =>'/^[0-9]+(.[0-9]{1,2})?$/',
                        'messages' =>'Der Preis muss das Format 123 bzw. 123.99 haben'
                    ),
                ),

            ),
            'value' => "1,00",
            'required'   => true,
        ));

        $this->addElement("text", "kcal", array(
            'label'      => 'Kalorien',
            'filters'    => array(
                'StringTrim',
                'StripTags'
            ),
            'validators' => array(
                array(
                    'StringLength',
                    false,
                    [1, 5]
                ),
                array(
                    'validator' => "Regex",
                    'options'=>array(
                        'pattern' =>'/^[0-9]+$/',
                        'messages' =>'Nur ganzzahlige Werte aus Ziffern'
                    ),
                ),
            ),
            'required'   => true,
        ));

        $this->addElement("textarea", "description", array(
            'label'      => 'Beschreibung',
            'rows'       => 9,
            'filters'    => array(
                'StringTrim',
                'StripTags',
            ),
            'validators' => array(
                array(
                    'StringLength',
                    false,
                    [1, 50]
                ),
            ),
            'required'   => false,
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
            'label'      => 'speichern',
        ));
    }



}

