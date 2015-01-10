<?php

class IndexController extends Zend_Controller_Action
{
    /**
     * @var Smoothieme_Cart
     */
    private $cart;

    public function init()
    {
        $this->cart=Zend_Registry::get("cart");
        $this->cart->addItem("1");


        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        echo "Check this cart out! :D ";
        var_dump($this->cart->getCartContents());
    }

    public function deleteAction()
    {
        // action body
    }

    public function fruitsAction()
    {
        // action body
    }


}





