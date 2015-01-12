<?php

class RegisterController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $registerForm = new Application_Form_Register();
        $registerForm->setName("register")
            ->setMethod("post");

        $this->view->registerForm = $registerForm;

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
                $comment = new Application_Model_Address($form->getValues());
                $mapper  = new Application_Model_AddressMapper();
                $mapper->save($comment);
                return $this->_helper->redirector('index');
            }
        }
    }

    public function registerAction()
    {
        $request = $this->getRequest();
        $form    = new Application_Form_Register();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
                $comment = new Application_Model_Register($form->getValues());
                $mapper  = new Application_Model_RegisterMapper();
                $mapper->save($comment);
                return $this->_helper->redirector('index');
            }
        }
        $this->view->form = $form;
    }


}
