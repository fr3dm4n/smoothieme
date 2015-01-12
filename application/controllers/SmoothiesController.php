<?php

class SmoothiesController extends Zend_Controller_Action
{

    public function init()
    {
        //Lade Scripte für Backend
        $this->view->headScript()->appendFile("/js/dist/backend.min.js");

    }

    /**
     * Anzeige der Smoothies
     */
    public function indexAction()
    {
        $smoothieMapper=new Application_Model_SmoothieMapper();
        $this->view->smoothies=$smoothieMapper->fetchAll();


    }

    /**
     * Neues Smoothie eingefügt
     * @throws Zend_Form_Exception
     */
    public function addAction()
    {
        $req = $this->getRequest();

        // Formular bereitstellen
        $smoothieForm = new Application_Form_Smoothie();
        $smoothieForm->setAction($this->view->url(array(
            "controller" => "Smoothies",
            "action"     => "add"
        )));
        if ($this->getRequest()->isPost()) {
            if ($smoothieForm->isValid($req->getPost())) {
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

                    $smoothieMapper=new Application_Model_SmoothieMapper();
                    $smoothieMapper->save($smoothie);

                    $this->_helper->flashMessenger->setNamespace("success")->addMessage("Neuer Smoothie gespeichert!");
                    $this->redirect($this->view->url(array('controller' => "Smoothies", "action" => "index")));
                }



            }
        }
        //Früchte einfügen
        $fruitsMapper=new Application_Model_FruitMapper();
        $this->view->fruits=$fruitsMapper->fetchAll();

        $this->view->smoothieForm = $smoothieForm;
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
        if (!Zend_Auth::getInstance()->hasIdentity() || Zend_Auth::getInstance()->getIdentity()->role!=="admin") {

            $this->_helper->flashMessenger
                ->setNamespace("warning")
                ->addMessage("Ihre Sitzung ist abgelaufen");
            $this->redirect(array('controller' => "index","action" => "index"));
            exit();
        }

    }

    /**
     * Lösche Smoothie
     */
    public function deleteAction() {
        $req = new Zend_Controller_Request_Http();
        $post=$req->getPost();

        //Abbruch
        if(!isset($post["id"]) || empty($post["id"])){
            $this->redirect($this->view->url(array('controller' => "Smoothies", "action" => "index")));
            return;
        }

        $id=intval($post["id"]);
        $smoothie = new Application_Model_Smoothie();
        $mapper = new Application_Model_SmoothieMapper();
        $mapper->find($id,$smoothie);
        $name=$smoothie->getName();
        //Lösche aus Datenbank
        if(!empty($name)){
            if($mapper->delete($id)>0){
                $this->_helper->flashMessenger->setNamespace("success")->addMessage("Smoothie gelöscht");
            }else{
                $this->_helper->flashMessenger->setNamespace("error")->addMessage("Smoothie konnte nicht gelöscht werden");
            }
        }else{
            $this->_helper->flashMessenger->setNamespace("error")->addMessage("Ungültige Parameter!");
        }

        // action body
        $this->redirect($this->view->url(array('controller' => "Smoothies", "action" => "index")));
    }
}





