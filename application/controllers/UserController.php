<?php

class UserController extends Zend_Controller_Action
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
                $comment = new Application_Model_Guestbook($form->getValues());
                $mapper = new Application_Model_GuestbookMapper();
                $mapper->save($comment);
                return $this->_helper->redirector('index');
            }
        }
    }

    public function changeAction()
    {
        // action body
    }


}



