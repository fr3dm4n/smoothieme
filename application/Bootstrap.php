<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initDocType() {
        $this->bootstrap('view');

        $view = $this->getResource('view');
        $view->doctype('HTML5');


        $view->headLink()
            ->appendStylesheet("/css/styles.css");
        $view->headScript()
            ->appendFile("/js/jquery-2.1.1.min.js")
            ->appendFile("/js/bootstrap.js");

    }

}

