<?php

class CheckoutController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function preDispatch()
    {
        //Deaktiviere layout wenn ajax-request
        $request=new Zend_Controller_Request_Http();
        if($request->isXmlHttpRequest()){
            $this->_helper->layout()->disableLayout();
            $this->view->isAjax=true;
        }else{
            $this->view->isAjax=false;
        }
        // Validiere Zugang
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->flashMessenger
                ->setNamespace("warning")
                ->addMessage("Sie müssen sich zuerst anmelden.");
            $this->redirect(array('controller' => "index","action" => "index"));
            exit();
        }

    }

    public function indexAction()
    {
        // action body
        $orderprice = 0;
        $cart = Zend_Registry::get("cart");
        $cartcontent= $cart->getCartContents();
        $mapper = new Application_Model_SmoothieMapper();
        $smoothies = array();
        foreach($cartcontent as $id => $amount){
            $smoothie = new Application_Model_Smoothie();
            $mapper->find($id,$smoothie);
            $price =  $smoothie->getPrice();
            $smoothies[] = array(
                "smoothie"=>$smoothie,
                "amount" => $amount,
                "pricetotal"=>$price*$amount
            ) ;
            $orderprice += $price*$amount;
        }
        $this->view->smoothies = $smoothies;
        $this->view->orderprice = $orderprice;
    }

    public function sendMailAction()
    {

        $config = array('auth' => 'login',
            'username' => 'smoothieme@itsh-solution.de',
            'password' => 'smoo1234',
            'ssl' => 'ssl',
            'port' => 465);

        $user = Zend_Auth::getInstance()->getStorage()->read();

        $mapper = new Application_Model_CustomerMapper();

        $customer = $mapper->getCustomerByAccId($user->ID);
        $lastname = $customer->getLastname();

        $email = $user->email;
        $this->view->email = $email;
        $name =  $user->name;

        $tr = new Zend_Mail_Transport_Sendmail('smoothieme@itsh-solution.de');
        Zend_Mail::setDefaultTransport($tr);
        $trsmtp = new Zend_Mail_Transport_Smtp('smtprelaypool.ispgateway.de',$config);
        Zend_Mail::setDefaultTransport($trsmtp);

        if($user != null) {
            $mail = new Zend_Mail('UTF-8');
            $mail->setBodyHtml('Hallo Herr '.$lastname. '<br> Vielen Dank für Ihre Bestellung bei Smoothieme. <br> Dies ist eine Automatisch generierte Email, bitte anworten Sie nicht darauf. <br> Mit freundlichen Grüßen <br> Ihr Smoothieme Team')
                ->setFrom('smoothieme@itsh-solution.de', 'SmoothieMe Shop')
                ->addTo($email, $name)
                ->setSubject('Ihre Bestellung bei Smoothieme')
                ->send();
        }
        else
            echo "Die Email konnte nicht versendet werden!";
    }



}



