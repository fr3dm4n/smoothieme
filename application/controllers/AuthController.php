<?php

class AuthController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $loginForm = new Application_Form_Auth();

        $this->view->isAjax = false;

        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout->disableLayout();
            $this->view->isAjax = true;
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($loginForm->isValid($request->getPost())) {
                if ($this->_process($loginForm->getValues()['login'])) {
                    $this->_helper->redirector('index', 'index');
                }
            }
        }
        $this->view->loginForm = $loginForm;
    }

    protected function _process($values)
    {
// Get our authentication adapter and check credentials
        $adapter = $this->_getAuthAdapter();
        $adapter->setIdentity($values['username']);
        $adapter->setCredential($values['password']);

        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter);
        if ($result->isValid()) {
            $user = $adapter->getResultRowObject();
            $auth->getStorage()->write($user);
            return true;
        }
        return false;
    }

    protected function _getAuthAdapter()
    {

        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);

        $authAdapter->setTableName('accounts')
            ->setIdentityColumn('name')
            ->setCredentialColumn('password')
            ->setCredentialTreatment('SHA1(CONCAT(?,salt))');

        return $authAdapter;

    }

    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('index','index'); // back to login page
    }


}



