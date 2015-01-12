<?php

class CartController extends Zend_Controller_Action
{

    /**
     * @var Smoothieme_Cart
     */
    private $cart = null;

    public function init()
    {
        /* Initialize action controller here */
        $this->cart= Zend_Registry::get("cart");
    }

    /**
     * Darstellung warenkorb
     */
    public function indexAction()
    {
        $this->view->cartItems=[];
        $this->view->summe=0;

        foreach($this->cart->getCartContents() as $cartItemID=>$cartItemCount){
            $smoothie=new Application_Model_Smoothie();
            $smoothieMapper=new Application_Model_SmoothieMapper();
            $smoothieMapper->find($cartItemID,$smoothie);

            $this->view->cartItems[]=["amount"=>$cartItemCount,"smoothie"=>$smoothie];

            $this->view->summe+=$cartItemCount*$smoothie->getPrice();
        }

        $this->view->cart=$this->cart;
    }


    /**
     * Warenkorb leeren
     * @return mixed
     */
    public function clearAction()
    {
        if ($this->getRequest()->isPost()) {
            $this->cart->clear();

        }
    }

    /**
     * Ändert Anzahl eines Artikels
     */
    public function changeAction()
    {
        if ($this->getRequest()->isPost()) {
            $params=$this->getRequest()->getParams();

            if(isset($params["add"])){
                $this->cart->addItem(intval($params["add"]));
            }else
            if(isset($params["sub"])){
                $this->cart->removeItem(intval($params["sub"]),1);
            }else
            if(isset($params["remove"])){
                $this->cart->removeItem(intval($params["remove"]),$this->cart->getAmount()); // sollte reichen ;)
            }else
            if(isset($params["amount"])){
                $this->cart->setAmount(intval($params["id"]),$params["amount"]);
            }
        }
    }

    /**
     * Entfernt einen Artikel
     */
    public function removeAction()
    {

    }


}









