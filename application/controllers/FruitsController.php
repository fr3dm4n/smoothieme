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
            ->setMethod("post");

        $this->view->fruitsForm = $fruitsForm;
    }

    /**
     * Deaktiviere layout wenn Ajax-request
     */
    public function preDispatch(){
        $request=new Zend_Controller_Request_Http();
        if($request->isXmlHttpRequest()){
            $this->_helper->layout()->disableLayout();
            $this->view->isAjax=true;
        }else{
            $this->view->isAjax=false;
        }

    }

}



