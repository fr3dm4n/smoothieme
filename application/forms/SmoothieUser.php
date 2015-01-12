<?php

class Application_Form_SmoothieUser extends Twitter_Bootstrap_Form
{
    /**
     * Konst.
     * @param null $options
     */
    public function __construct($options = null) {
        parent::__construct($options);
    }

    /**
     * init
     */
    public function init() {
        $this->setName("fruits")->setMethod("post");

        $this->addElement("text", "name", array(
            'label'      => "Name",
            'required'   => true,
            "placeholder"=>"z.B. grünes Monster",
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

        $smoothieSizes=Application_Model_Smoothie::$sizes;


        $sizeOptions=[];
        foreach($smoothieSizes as $sizeName=>$size){
            $sizeOptions[$sizeName]=$sizeName." (".$size."ml)";
        }
        $size=new Zend_Form_Element_Select("size");
        $size->addMultiOptions($sizeOptions)
            ->setLabel("Größe")
            ->setRequired(true)
            ->addValidator('NotEmpty');
        $this->addElement($size);

        $this->addElement('submit', 'submit', array(
            'buttonType' => Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
            'label'      => 'speichern',
        ));


    }


}
