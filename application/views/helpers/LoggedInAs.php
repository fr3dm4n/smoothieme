<?php
/**
 * Created by PhpStorm.
 * User: Sadeq
 * Date: 14.12.2014
 * Time: 14:42
 */

class Zend_View_Helper_LoggedInAs extends Zend_View_Helper_Abstract {

    public function loggedInAs ()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $username = $auth->getIdentity()->username;
            $logoutUrl = $this->view->url(array('controller'=>'auth',
                'action'=>'logout'), null, true);
            return 'Welcome ' . $username . '. Logout';
        }

        $request = Zend_Controller_Front::getInstance()->getRequest();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        if($controller == 'auth' && $action == 'index') {
            return '';
        }
        $loginUrl = $this->view->url(array('controller'=>'auth', 'action'=>'index'));
        return 'Login';
    }
} 