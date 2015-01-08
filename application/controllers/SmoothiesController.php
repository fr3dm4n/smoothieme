<?php

class SmoothiesController extends Zend_Controller_Action
{

    public function init()
    {

    }

    public function indexAction()
    {
        // action body
    }

    public function addAction()
    {
        // action body
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
            $this->_helper->flashMessenger
                ->setNamespace("warning")
                ->addMessage("Ihre Sitzung ist abgelaufen");
            $this->redirect(array('controller' => "index","action" => "index"));
            exit();
        }

    }

}



