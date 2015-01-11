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

    public function addAction()
    {
        // action body
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
        if (Zend_Auth::getInstance()->hasIdentity()!="admin") {
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





