<?php

class FruitsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction() {

    }

    public function addAction()
    {
        $fruitsForm=new Application_Form_Fruits();
        $fruitsForm->setName("fruits")
            ->setMethod("post")
            ->setAction("/Fruits/add");

        $this->view->fruitsForm = $fruitsForm;

    }

    /**
     * PreDispatch
     */
    public function preDispatch(){
        //Deaktiviere layout wenn ajax-request
        $request=new Zend_Controller_Request_Http();
        if($request->isXmlHttpRequest()){
            $this->_helper->layout()->disableLayout();
            $this->view->isAjax=true;
        }else{
            $this->view->isAjax=false;
        }
        // Validiere Zugang
        if (Zend_Auth::getInstance()->hasIdentity()!="admin") {
            require("Smoothieme/Exception/Unauthorized.php");
            throw new Smoothieme_Exception_Unauthorized("Keine Authorisation");
        }

    }

}



