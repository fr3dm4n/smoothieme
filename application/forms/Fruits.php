<?php

class Application_Form_Fruits extends Twitter_Bootstrap_Form {
    /**
     * Konst.
     * @param null $options
     */
    public function __construct($options=null){
        parent::__construct($options);
    }
    /**
     * Initialisiert Fruit-FOrm elemente
     * @throws Zend_Form_Exception
     */
    public function init() {


        $this->setName("fruits");

        $this->addElement("text","name", array(
            'label'      => "Name",
            'required'   => true,
            'filters'    => array(
                'StringTrim',
                'StripTags'
            ),
            'validators' => array(
                array(
                    'Stringlength',
                    false,
                    [10,70]
                )
            ),
        ));




        $this->addElement("text","color", array(
            'label'      => 'Farbe',
            'filters'    => array(
                'StringTrim',
                'StripTags'
            ),
            'validators' => array(
                array(
                    'StringLength',
                    false,
                    [6,6]
                ),
            ),
            'required'   => true,
            'size'       => 6,
        ));


        $this->addElement("text","price",array(
            'label'      => 'Preis (je 100 ml)',
            'filters'    => array(
                'StringTrim',
                'StripTags'
            ),
            'validators' => array(
                array(
                    'StringLength',
                    false,
                    [1, 14]
                ),
            ),
            'required'   => true,
        ));

        $this->addElement("text","kcal",array(
            'label'      => 'Kcal (je 100ml)',
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
            ),
            'prepend'=>"kcal",
            'required'   => true,
        ));

        $this->addElement("textarea","desc",array(
            'label'      => 'Beschreibung',
            'rows'=> 4,
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
            ),
            'required'   => false,
        ));

        // FILE_UPLOAD

        $fruitPhoto = new Zend_Form_Element_File('fruitphoto');
        $fruitPhoto->setLabel('Frucht-Icon');
        try {
            $fruitPhoto->setDestination(Zend_Registry::get('config')->backend->paths->tmpdir);
        } catch(Exception $e){
            $this->addErrorMessages(array("Verzeichnis <pre>".Zend_Registry::get('config')->backend->paths->tmpdir."</pre> entweder nicht beschreibbar oder existiert nicht"));
        }
        // ensure only one file
        $fruitPhoto->addValidator('Count', false, 1);
        // max 2MB
        $postMax=self::getPhpDateSizeInByte(ini_get("post_max_size"));

        $uploadMax=self::getPhpDateSizeInByte(ini_get("upload_max_filesize"));
        $uploadLimit=min($uploadMax,$postMax);
        var_dump($uploadLimit);
        $fruitPhoto->addValidator('Size', false, 2097152)
            ->setMaxFileSize(2097152);
//        // only JPEG, PNG, or GIF
//        $fruitPhoto->addValidator('Extension', false, 'jpg,png,gif');
//        $fruitPhoto->setValueDisabled(true);
//        $this->addElement($fruitPhoto, 'photo');

        $this->addElement('submit', 'submit', array(
            'buttonType' => Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
            'label'      => 'speichern',
        ));
    }

    /**
     * Rechnet Dateigrößen von 20M zu 20*1024^2 Byte um
     * @param $val
     * @return int|string
     */
    private static function getPhpDateSizeInByte($val){
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        switch($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }

        return $val;
    }

}

