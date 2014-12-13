<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initDocType() {
        $this->bootstrap('view');

        $view = $this->getResource('view');
        $view->doctype('HTML5');


        $view->headLink()
            ->appendStylesheet("/css/bootstrap.css")
            ->appendStylesheet("/css/styles.css");
        $view->headScript()
           /* ->appendFile("/js/dist/vendors.min.js")*/
            ->appendFile("/js/src/basic.min.js")
            ->appendFile("/js/vendors/01-jquery-2.1.1.min.js")
            ->appendFile("/js/vendors/02-bootstrap.min.js");

    }

}

