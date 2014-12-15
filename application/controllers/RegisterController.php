<?php

class RegisterController extends Zend_Controller_Action
{
    // snipping indexAction()...

    public function indexAction()
    {
        $request = $this->getRequest();
        $form    = new Application_Form_Register();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
                $comment = new Application_Model_Guestbook($form->getValues());
                $mapper  = new Application_Model_GuestbookMapper();
                $mapper->save($comment);
                return $this->_helper->redirector('index');
            }
        }

        $this->view->form = $form;
    }
}