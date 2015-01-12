<?php

class AddressController extends Zend_Controller_Action
{
    /**
     * @var String Temporäres Verzeichnis
     */
    private $tempdir = '';

    /**
     * Init
     * @throws Zend_Exception
     */
    public function init()
    {
        //Lade Scripte für Backend
        $this->view->headScript()->appendFile("/js/dist/backend.min.js");
        $this->tempdir = realpath(Zend_Registry::get('config')->backend->tmpdir->path);

        //Prüfe voraussetungen
        $this->view->unfullfilledRequiredments = false;
        $this->checkRequirements();

        //Entferne alte temporäre Dateien
        $this->cleanTempFiles($this->tempdir);
    }

    private function checkRequirements() {
        //Prüft Verzeichnis
        $backendConfig = Zend_Registry::get('config')->backend;
        $errorMessenger = $this->_helper->flashMessenger->setNamespace("error");

        if (!is_writeable($this->tempdir)) {
            $this->view->unfullfilledRequiredments = true;
            $errorMessenger->addMessage("Temporäres Verzeichnis nicht beschreibbar");
        }
    }

    /**
     * Leere Temporäres Verzeichnis
     *
     * @param string $dir
     * @param int $old Alter der Dateien in Sek. die gelöscht werden sellen
     */
    private function cleanTempFiles($dir, $old = 3601) {
        //Delete temporary files
        $t = time();

        $iterator = new \DirectoryIterator ($dir);
        foreach ($iterator as $info) {
            if ($info->isFile() && ($t - $info->getMTime()) > $old && $info->getFilename() != ".gitkeep") {
                unlink($dir . "/" . $info->getFilename());
            }
        }
    }

    /**
     * Übersicht aller Adresse
     */
    public function indexAction()
    {
        $mapper = new Application_Model_AddressMapper();
        $this->view->allAddresses = $mapper->fetchAll();

    }

    public function changeAction()
    {

    }

    public function addAction()
    {
        $req = $this->getRequest();

        // Formular bereitstellen
        $addressForm = new Application_Form_Address();

        if ($this->getRequest()->isPost()) {
            if ($addressForm->isValid($req->getPost())) {
                $address = new Application_Model_Address($addressForm->getValues());
                $mapper = new Application_Model_AddressMapper();
                $id = $mapper->save($address);

                $this->_helper->flashMessenger->setNamespace("success")->addMessage($address->getName() . " gespeichtert");
                return $this->_helper->redirector("index");
            }
        }

        $addressForm->setAction($this->view->url(array(
            "controller" => "Address",
            "action"     => "add"
        )));

        $this->view->addressForm = $addressForm;

    }

    public function deleteAction()
    {

    }

}







