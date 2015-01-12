<?php

class CartController extends Zend_Controller_Action {

    /**
     * @var Smoothieme_Cart
     */
    private $cart = null;

    public function init() {
        /* Initialize action controller here */
        $this->cart = Zend_Registry::get("cart");
    }

    /**
     * Darstellung warenkorb
     */
    public function indexAction() {
        $this->view->cartItems = [];
        $this->view->summe = 0;

        foreach ($this->cart->getCartContents() as $cartItemID => $cartItemCount) {
            $smoothie = new Application_Model_Smoothie();
            $smoothieMapper = new Application_Model_SmoothieMapper();
            $smoothieMapper->find($cartItemID, $smoothie);

            $this->view->cartItems[] = ["amount" => $cartItemCount, "smoothie" => $smoothie];

            $this->view->summe += $cartItemCount * $smoothie->getPrice();
        }

        $this->view->cart = $this->cart;
    }


    /**
     * Warenkorb leeren
     * @return mixed
     */
    public function clearAction() {
        if ($this->getRequest()->isPost()) {
            $this->cart->clear();
        }
        $this->cart->__destruct();
        return $this->_helper->redirector("index");
    }

    /**
     * Ändert Anzahl eines Artikels
     */
    public function changeAction() {
        if ($this->getRequest()->isPost()) {
            $params = $this->getRequest()->getParams();
            if (isset($params["amount"])) {
                $this->cart->setAmount(intval($params["id"]), intval($params["amount"]));
            }
        }
        $this->cart->__destruct();
        return $this->_helper->redirector("index");
    }

    /**
     * Ändert Anzahl eines Artikels
     */
    public function addAction() {
        if ($this->getRequest()->isPost()) {
            $params = $this->getRequest()->getParams();

            if (isset($params["add"])) {
                $this->cart->addItem(intval($params["add"]));
            }
        }
        $this->cart->__destruct();
        return $this->_helper->redirector("index");
    }

    /**
     * Ändert Anzahl eines Artikels
     */
    public function subAction() {
        if ($this->getRequest()->isPost()) {
            $params = $this->getRequest()->getParams();

            if (isset($params["sub"])) {
                $this->cart->removeItem(intval($params["sub"]), 1);
            }
        }
        $this->cart->__destruct();
        return $this->_helper->redirector("index");
    }

    /**
     * Ändert Anzahl eines Artikels
     */
    public function removeAction() {
        if ($this->getRequest()->isPost()) {
            $params = $this->getRequest()->getParams();

            if (isset($params["remove"])) {
                $this->cart->removeItem(intval($params["remove"]), $this->cart->getAmount()); // sollte reichen ;)
            }
        }
        $this->cart->__destruct();
        return $this->_helper->redirector("index");
    }


}









