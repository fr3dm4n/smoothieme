<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $req = $this->getRequest();

        $userForm = new Application_Form_User();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
                $user = new Application_Model_User($form->getValues());
                $mapper = new Application_Model_UserMapper();

                $this->_helper->flashMessenger->setNamespace("success")->addMessage($user->getName() . " gespeichtert");
                return $this->_helper->redirector("index");
            }
        }
    }

    public function changeAction()
    {
        // action body
    }


}



