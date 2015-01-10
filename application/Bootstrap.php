<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
    /**
     * Initialisiert Warenkorb-Instanz
     */
    protected function _initCart(){
        $cart=new Smoothieme_Cart();
        Zend_Registry::set('cart', $cart);
    }

    /**
     * Übersetzung der Validations-Nachrichten
     * @throws Zend_Validate_Exception
     */
    protected function _initTranslation() {
        $translator = new Zend_Translate(
            array(
                'adapter' => 'array',
                'content' => APPLICATION_PATH . '/../resources/languages',
                'locale' => 'de_DE',
                'scan' => Zend_Translate::LOCALE_DIRECTORY
            )
        );
        Zend_Validate_Abstract::setDefaultTranslator($translator);
    }

    /**
     * Initialisiert den HTML-Kopf
     */
    protected function _initHtmlHead() {
        $this->bootstrap('view');

        $view = $this->getResource('view');
        $view->doctype('HTML5');

        $view->headLink()
            ->appendStylesheet("/css/bootstrap.css")
            ->appendStylesheet("/css/styles.css");

        $view->headScript()
            ->appendFile("/js/dist/vendors.min.js")
            ->appendFile("/js/dist/basic.min.js");


        //save Configfile in Registry 4 later
        Zend_Registry::set("config",new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV));

    }
}

