<?php

class SmoothiesUserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->view->headScript()->appendFile("/js/dist/backend.min.js");
    }

    public function indexAction()
    {
        // action body
        $smoothieMapper=new Application_Model_SmoothieMapper();
        $this->view->smoothies=$smoothieMapper->fetchAll();



    }

    public function addtocartAction(){

        $id = $this->getRequest()->getPost('addtocart');

        $this->cart=Zend_Registry::get("cart");
        $this->cart->addItem($id);

        $this->cart->__destruct();
        $this->redirect('/smoothies-user');
    }



}



