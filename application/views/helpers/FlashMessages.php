<?php



/**
 * User: Alfred Feldmeyer
 * Date: 01.01.2015
 * Time: 13:18
 */
class Zend_View_Helper_FlashMessages extends Zend_View_Helper_Abstract {
    /**
     * @param bool $currentMessages Liefert die Nachrichten, vor Redirect aus!
     * @return string
     */
    public function flashMessages($currentMessages=false) {

        $output = '';
        $flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        $sucMess["ns"]="success";
        $sucMess["title"]="Erfolg";
        $sucMess["typeClass"]="alert-success";

        $warnMess["ns"]="warning";
        $warnMess["title"]="Warnung";
        $warnMess["typeClass"]="alert-warning";

        $errMess["ns"]="error";
        $errMess["title"]="Fehler";
        $errMess["typeClass"]="alert-danger";

        foreach([$errMess, $warnMess, $sucMess] as $msgTypes){ // Je Nachrichtentyp
            $flashMessenger->setNamespace($msgTypes["ns"]);
            if($flashMessenger->hasMessages()===false && $flashMessenger->hasCurrentMessages()===false){
                continue;
            }
            if($currentMessages===true){
                $msgs=$flashMessenger->getCurrentMessages();
                $flashMessenger->clearCurrentMessages();
            }else{
                $msgs=$flashMessenger->getMessages();
            }

            foreach($msgs as $msg){
                $output.='<div class="alert '.$msgTypes["typeClass"].' alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>'.$msgTypes["title"].'</strong> '.$msg.'
                        </div>';
            }
        }

        return $output;
    }
}