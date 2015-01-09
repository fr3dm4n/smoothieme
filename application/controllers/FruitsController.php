<?php

class FruitsController extends Zend_Controller_Action {
    public function init() {
        $this->view->unfullfilledRequiredments = false;
        $this->checkRequirements();
    }

    /**
     * Prüft ob alle Notwendigkeiten gegeben sind
     */
    private function checkRequirements() {
        //Prüft Verzeichnis
        $backendConfig = Zend_Registry::get('config')->backend;
        $errorMessenger = $this->_helper->flashMessenger->setNamespace("error");

        $tempdir = $backendConfig->paths->tmpdir;
        $fruitsdir = $backendConfig->paths->fruits;
        if (!file_exists($tempdir)) {
            $this->view->unfullfilledRequiredments = true;
            $errorMessenger->addMessage("Temporäres Verzeichnis existiert nicht");
        }
        if (!is_writeable($tempdir)) {
            $this->view->unfullfilledRequiredments = true;
            $errorMessenger->addMessage("Temporäres Verzeichnis nicht beschreibbar");
        }
        if (!file_exists($fruitsdir)) {
            $this->view->unfullfilledRequiredments = true;
            $errorMessenger->addMessage("Frucht-Abbildungs-Verzeichni existiert nicht");
        }
        if (!is_writeable($fruitsdir)) {
            $this->view->unfullfilledRequiredments = true;
            $errorMessenger->addMessage("Frucht-Abbildungs-Verzeichnis Verzeichnis nicht beschreibbar");
        }
        if (!extension_loaded('imagick') && !class_exists("Imagick")) {
            $this->view->unfullfilledRequiredments = true;
            $errorMessenger->addMessage("Imagick ist nicht installiert");
        }
    }

    /**
     * Prüft ob es sich um einen Einzelbild-Upload per Ajax handelt
     */
    private function isSinglePictureUpload(){
        return empty($_POST) && count($_FILES)==1 && $this->view->isAjax;
    }

    public function indexAction() {

    }

    /**
     * Fügt neue Frucht hinzu
     * @throws Zend_Form_Exception
     */
    public function addAction() {
        //Einzelbild-UPload bearbeiten
        if($this->isSinglePictureUpload()){
            $flashMsnger=$this->_helper->flashMessenger;
            $photo = new Zend_File_Transfer();
            $tmpDir=Zend_Registry::get('config')->backend->paths->tmpdir;
            try {
                $photo->setDestination($tmpDir);
            } catch (Exception $e) {
                $flashMsnger->setNamespace("error")
                    ->addMessage("Verzeichnis <pre>" . $tmpDir . "</pre> entweder nicht beschreibbar oder existiert nicht");
            }
            //Token für vorübergehende interne Speicherung
            $token=md5(microtime());

            // Gibt alle bekannten internen Datei Informationen zurück
            $files = $photo->getFileInfo();
            $extension="";

            foreach ($files as $file => $info) {
                //auch, wenn wir den Upload auf ein Bild beschränkt haben...
                if ($files == 1) {
                    $flashMsnger->setNamespace("error")->addMessage("Sie dürfen nur eine Datei hochladen");
                    continue;
                }
                // Datei hochgeladen ?
                if (!$photo->isUploaded($file)) {
                    $flashMsnger->setNamespace("error")->addMessage("Sie haben keine Datei hochgeladen!");
                    continue;
                }
                //Datei valide?
                if (!$photo->isValid($file)) {
                    $flashMsnger->setNamespace("error")->addMessage("Sie haben eine ungültige Datei hochgeladen!");
                    continue;
                }

                //Datei umbenennen
                $extension = pathinfo($info["name"], PATHINFO_EXTENSION);

                $photo->addFilter('Rename', array(
                    'target'    => $token . '.' . $extension,
                    'overwrite' => true
                ));

                //Datei in Zielverzeichnis-schieben
                $photo->receive();

                //Erfolgs-Meldung
                $flashMsnger->setNamespace("success")->addMessage("Bild wurde zur Änderung übertragen");
            }

            // Bereite Nachrichten für JSON vor
            $return=array();
            $return["preview"]=base64_encode(file_get_contents($tmpDir."/".$token.".".$extension));
            foreach(["error","warning","success"] as $type){
                $flashMsnger->setNamespace($type);
                $return["msg"][$type]=$flashMsnger->getCurrentMessages();
                $flashMsnger->clearCurrentMessages();
            }
            $this->_helper->json($return);
            exit();
        }


        // Formular bereitstellen
        $fruitsForm = new Application_Form_Fruits();
        $fruitsForm->setName("fruits")->setMethod("post")->setAction($this->view->url(array(
                "controller" => "Fruits",
                "action"     => "add"
            )));

        $this->view->fruitsForm = $fruitsForm;

    }

    /**
     * PreDispatch
     */
    public function preDispatch() {
        //Deaktiviere layout wenn ajax-request
        $request = new Zend_Controller_Request_Http();
        if ($request->isXmlHttpRequest()) {
            $this->_helper->layout()->disableLayout();
            $this->view->isAjax = true;
        } else {
            $this->view->isAjax = false;
        }
        // Validiere Zugang
        if (Zend_Auth::getInstance()->hasIdentity() != "admin") {
            $this->_helper->flashMessenger->setNamespace("warning")->addMessage("Ihre Sitzung ist abgelaufen");
            $this->redirect(array('controller' => "index", "action" => "index"));
            exit();
        }

    }

}



