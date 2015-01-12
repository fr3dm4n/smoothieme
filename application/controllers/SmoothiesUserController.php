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

    public function addtocartAction()
    {

        $id = $this->getRequest()->getPost('addtocart');

        $this->cart=Zend_Registry::get("cart");
        $this->cart->addItem($id);


        $this->cart->__destruct();


        $this->_helper->flashMessenger
            ->setNamespace("success")
            ->addMessage("Ihr Smoothie wurde in den Warenkorb gelegt.");
        $this->redirect('/smoothies-user');
    }

    public function addAction()
    {
        // Validiere Zugang
        if (!Zend_Auth::getInstance()->hasIdentity()) {

            $this->_helper->flashMessenger
                ->setNamespace("warning")
                ->addMessage("Sie müssen sich anmelden um smoothies mixen zu können.");
            $this->redirect(array('controller' => "index","action" => "index"));
            exit();
        }
        $req = $this->getRequest();

        // Formular bereitstellen

        $smoothieUserForm = new Application_Form_SmoothieUser();
        $smoothieUserForm->setAction($this->view->url(array(
            "controller" => "smoothies-user",
            "action"     => "add"
        )));
        if ($this->getRequest()->isPost()) {
            if ($smoothieUserForm->isValid($req->getPost())) {
                //Zustätzliche Validatoren

                //Es müssen Früchte übergeben worden sein!
                $data=$req->getParams();

                if(count($data["fruitInSmoothie"])<1){
                    $this->_helper->flashMessenger->setNamespace("error")->addMessage("Es wurden keine Früchte übergeben!");
                }
                //Ids valide Früchte?

                foreach(array_keys($data["fruitInSmoothie"]) as $fruitID){
                    $fruitmapper= new Application_Model_FruitMapper();
                    $fruitID=intval($fruitID);

                    $fruit=new Application_Model_Fruit();
                    $fruitmapper->find($fruitID,$fruit);
                    if(is_null($fruit->getName())){
                        $this->_helper->flashMessenger->setNamespace("error")->addMessage("Ungültige FruchtID wurde übergeben!");
                        break;
                    }
                }
                //Summen okay?
                $summeGes=0;
                foreach($data["fruitInSmoothie"] as $fruitAmount){
                    $summeGes+=$fruitAmount;
                }
                //Summen darf kleiner 100 sein
                //, aber muss zwischen 1 und 100 liegen!
                if($summeGes>100){
                    $this->_helper->flashMessenger->setNamespace("error")->addMessage("Mehr als 100% Früchte gibts nirgends! :)");
                }
                if($summeGes<1){
                    $this->_helper->flashMessenger->setNamespace("error")->addMessage("Mindestens 1% Fruchtanteil notwendig!");
                }
                //Fehler aufgetreten?
                if(!$this->_helper->flashMessenger->setNamespace("error")->hasCurrentMessages()){
                    $smoothie=new Application_Model_Smoothie($data);
                    foreach($data["fruitInSmoothie"] as $fruitID=>$amount){
                        $fruitMapper=new Application_Model_FruitMapper();
                        $amountInMl=intval((Application_Model_Smoothie::$sizes[$data["size"]]*$amount)/100);
                        $fruit=new Application_Model_Fruit();
                        $fruitMapper->find($fruitID,$fruit);
                        $smoothie->addFruits($amountInMl,$fruit);
                    }
                    $loggedinuser = Zend_Auth::getInstance()->getStorage()->read();
                    $customermapper = new Application_Model_CustomerMapper();
                    $customer =$customermapper->getCustomerByAccId($loggedinuser->ID);

                    $smoothie->setCustomer($customer);
                    $smoothieMapper=new Application_Model_SmoothieMapper();
                    $smoothieMapper->save($smoothie);

                    $this->_helper->flashMessenger->setNamespace("success")->addMessage("Neuer Smoothie gespeichert!");
                    $this->redirect($this->view->url(array('controller' => "smoothies-user", "action" => "index")));
                }



            }
        }
        //Früchte einfügen
        $fruitsMapper=new Application_Model_FruitMapper();
        $this->view->fruits=$fruitsMapper->fetchAll();

        $this->view->smoothieUserForm = $smoothieUserForm;
    }

    /**
     * PreDispatch
     */
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
      /*  if (!Zend_Auth::getInstance()->hasIdentity()) {

            $this->_helper->flashMessenger
                ->setNamespace("warning")
                ->addMessage("Sie müssen sich anmelden um smoothies mixen zu können.");
            $this->redirect(array('controller' => "index","action" => "index"));
            exit();
        }*/

    }

}





