<?php


class FruitsController extends Zend_Controller_Action {

    /**
     * @var String Temporäres Verzeichnis
     */
    private $tempdir = '';

    /**
     * @var int Größtmöglicher Upload
     */
    private $uploadLimit = null;

    /**
     * Init
     * @throws Zend_Exception
     */
    public function init() {
        // max Dateigröße
        $postMax = self::getPhpDateSizeInByte(ini_get("post_max_size"));
        $uploadMax = self::getPhpDateSizeInByte(ini_get("upload_max_filesize"));

        //Multi-Part-post und Put-request wird damit abgedeckt
        //Human-Readable-Version
        $this->view->uploadLimitHR = $uploadMax < $postMax ? ini_get("upload_max_filesize") : ini_get("post_max_size");
        //Byte-Version
        $this->uploadLimit = min($uploadMax, $postMax);

        //Lade Scripte für Backend
        $this->view->headScript()->appendFile("/js/dist/backend.min.js");
        $this->tempdir = realpath(Zend_Registry::get('config')->backend->tmpdir->path);

        //Prüfe voraussetungen
        $this->view->unfullfilledRequiredments = false;
        $this->checkRequirements();

        //Entferne alte temporäre Dateien
        $this->cleanTempFiles($this->tempdir);
    }

    /**
     * Rechnet Dateigrößen von 20M zu 20*1024^2 Byte um
     * @param $val
     * @return int|string
     */
    private static function getPhpDateSizeInByte($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val) - 1]);
        switch ($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }

        return $val;
    }

    /**
     * Prüft ob alle Notwendigkeiten gegeben sind
     */
    private function checkRequirements() {
        //Prüft Verzeichnis
        $backendConfig = Zend_Registry::get('config')->backend;
        $errorMessenger = $this->_helper->flashMessenger->setNamespace("error");

        $fruitsdir = $backendConfig->fruitpic->path;
        if (!file_exists($this->tempdir)) {
            $this->view->unfullfilledRequiredments = true;
            $errorMessenger->addMessage("Temporäres Verzeichnis existiert nicht");
        }
        if (!is_writeable($this->tempdir)) {
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
     * Übersicht aller Früchte
     */
    public function indexAction() {
        $mapper = new Application_Model_FruitMapper();
        $this->view->allFruits = $mapper->fetchAll();
    }

    /**
     * Fügt neue Frucht hinzu
     * @throws Zend_Form_Exception
     */
    public function addAction() {
        //Einzelbild-UPload bearbeiten
        if ($this->isSinglePictureUpload()) {
            $this->prepareFruitPicture();
        }

        $req = $this->getRequest();

        // Formular bereitstellen
        $fruitsForm = new Application_Form_Fruits();

        if ($this->getRequest()->isPost()) {
            if ($fruitsForm->isValid($req->getPost())) {
                $fruit = new Application_Model_Fruit($fruitsForm->getValues());
                $mapper = new Application_Model_FruitMapper();
                $id = $mapper->save($fruit);
                if (!$this->movePreparedFruitPicture($id, $fruitsForm->getValues()["token"])) {
                    $this->_helper->flashMessenger->setNamespace("error")->addMessage("Fruchtabbildung konnte nicht gespeichert werden");
                }

                $this->_helper->flashMessenger->setNamespace("success")->addMessage($fruit->getName() . " gespeichtert");
                return $this->_helper->redirector("index");
            }
        }

        $fruitsForm->setAction($this->view->url(array(
                "controller" => "Fruits",
                "action"     => "add"
            )));

        $this->view->fruitsForm = $fruitsForm;
    }

    /**
     * Prüft ob es sich um einen Einzelbild-Upload per Ajax handelt
     */
    private function isSinglePictureUpload() {
        return empty($_POST) && count($_FILES) == 1 && $this->view->isAjax;
    }

    /**
     * Bereite eine Bild für den Upload vor
     * @throws Zend_Exception
     */
    private function prepareFruitPicture() {
        $flashMsnger = $this->_helper->flashMessenger;
        $photo = new Zend_File_Transfer();

        try {
            $photo->setDestination($this->tempdir);
        } catch (Exception $e) {
            $flashMsnger->setNamespace("error")->addMessage("Verzeichnis <pre>" . $this->tempdir . "</pre> entweder nicht beschreibbar oder existiert nicht");
        }
        //Token für vorübergehende interne Speicherung
        $token = md5(microtime());

        // Gibt alle bekannten internen Datei Informationen zurück
        $files = $photo->getFileInfo();
        $info = reset($files);
        $file = key($files);

        if (count($files) != 1) {
            $flashMsnger->setNamespace("error")->addMessage("Sie dürfen nur eine Datei hochladen");
        }

        // Datei hochgeladen ?
        if (!$photo->isUploaded($file)) {
            $flashMsnger->setNamespace("error")->addMessage("Sie haben keine Datei hochgeladen!");
        }

        // nur EINE Bild-Datei
        $photo->addValidator('Count', false, 1);
        $photo->addValidator('IsImage', false);

        $photo->addValidator('Size', false, $this->uploadLimit);
        // only JPEG, PNG, or GIF
        $photo->addValidator('Extension', false, 'jpg,png,jpeg,gif');


        //Datei valide?
        if (!$photo->isValid($file)) {
            foreach ($photo->getMessages() as $msg) {
                $flashMsnger->setNamespace("error")->addMessage($msg);
            }

        }

        //Datei umbenennen
        $extension = pathinfo($info["name"], PATHINFO_EXTENSION);
        $tmpFilename = $token . '.' . $extension;

        $photo->addFilter('Rename', array(
            'target'    => $tmpFilename,
            'overwrite' => true
        ));


        //Fehler bisher?
        if ($flashMsnger->setNamespace("error")->hasCurrentMessages()) {
            $flashMsnger->setNamespace("error");
            $return["msg"]["error"] = $flashMsnger->getCurrentMessages();
            $flashMsnger->clearCurrentMessages();
            $this->_helper->json($return);
            return; //Abbruch
        }

        //Datei in Zielverzeichnis-schieben
        $photo->receive();

        //Thumbnail erzeugen
        $thumb = new Smoothieme_Thumbnail($this->tempdir . "/" . $tmpFilename);
        $fruipicConfig = Zend_Registry::get('config')->backend->fruitpic;
        $thumb->resize($fruipicConfig->width, $fruipicConfig->height);
        if (unlink($this->tempdir . "/" . $tmpFilename)) { // Lösche aktuelles Bild
            $thumb->writeJPEG($this->tempdir . "/" . $tmpFilename);
        }

        //Erfolgs-Meldung
        $flashMsnger->setNamespace("success")->addMessage("Bild wurde zur Änderung übertragen");

        // Bereite Nachrichten für JSON vor
        $return = array();
        $return["token"] = $token;
        $return["preview"] = base64_encode(file_get_contents($this->tempdir . "/" . $tmpFilename));
        foreach (["error", "warning", "success"] as $type) {
            $flashMsnger->setNamespace($type);
            $return["msg"][$type] = $flashMsnger->getCurrentMessages();
            $flashMsnger->clearCurrentMessages();
        }
        $this->_helper->json($return);
        exit();
    }

    /**
     * Verschiebe vorbereitetes Bild in endgültiges Verzeichnis
     * @param $id
     * @param $token
     * @return bool
     */
    private function movePreparedFruitPicture($id, $token) {
        //Zur Sicherheit... ist es wirklich ein token?
        if (!preg_match("/[A-Fa-f0-9]{32}/", $token)) {
            return false;
        }
        if (file_exists($this->tempdir . "/" . $token . ".jpg")) {
            return rename($this->tempdir . "/" . $token . ".jpg", Zend_Registry::get('config')->backend->fruitpic->path . "/" . $id . ".jpg");
        } else {
            return false;
        }
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

    /**
     * Löscht eine Frucht
     * nur per ajax
     */
    public function deleteAction() {
        $req = new Zend_Controller_Request_Http();
        $post=$req->getPost();
        if(!isset($post["id"]) || empty($post["id"])){
            $this->redirect(array('controller' => "Fruits", "action" => "index"));
            return;
        }
        $id=intval($post["id"]);

        $fruit = new Application_Model_Fruit();
        $mapper = new Application_Model_FruitMapper();
        $mapper->find($id,$fruit);
        $name=$fruit->getName();
        //Lösche aus Datenbank
        $DBdelete=0;
        if(!empty($name)){
            $DBdelete=$mapper->delete($id);

        }else{
            $this->_helper->flashMessenger->setNamespace("error")->addMessage("Ungültige Parameter!");
            $this->redirect(array('controller' => "Fruits", "action" => "index"));
            return;
        }
        //Lösche Bild
        $fruit->setId($id);
        $fileDelete=$fruit->deletePicture();
        if(($fileDelete===true||is_null($fileDelete)) && $DBdelete==1){
            $this->_helper->flashMessenger->setNamespace("success")->addMessage("Frucht gelöscht");
        }else if($fileDelete===false){
            $this->_helper->flashMessenger->setNamespace("success")->addMessage("Frucht gelöscht");
            $this->_helper->flashMessenger->setNamespace("warning")->addMessage("Abbildung konnte nicht gelöscht werden");
        }else{

            $this->_helper->flashMessenger->setNamespace("error")->addMessage("Frucht konnte nicht gelöscht werden");
        }

        $this->redirect($this->view->url(array('controller' => "Fruits", "action" => "index")));
    }


}





