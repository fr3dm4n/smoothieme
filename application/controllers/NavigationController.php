<?php

class NavigationController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body

        $this->view->status="login";
    }

    public function adminAction()
    {
        // action body
    }
    public function userAction()
    {
        // action body
    }

}

