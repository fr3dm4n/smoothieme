<?php

class RegisterController extends Zend_Controller_Action
{
    public function init()
    {

    }

    public function indexAction()
    {
        $CustomerMapper = new Application_Model_CustomerMapper();
        $AccountMapper = new Application_Model_AccountMapper();

        $req = $this->getRequest();

        // Formular bereitstellen
        $registerForm = new Application_Form_Register();

        if ($this->getRequest()->isPost()) {

            if ($registerForm->isValid($req->getPost())) {
                $db = Zend_Db_Table_Abstract::getDefaultAdapter();

                try {
                    $db->beginTransaction();
                    $account = new Application_Model_Account();
                    $account->setName($registerForm->getValue("username"));
                    $account->setRole('user');
                    $account->setSalt(uniqid('', true));
                    $account->setPassword(sha1($registerForm->getValue("password") . $account->getSalt()));
                    $account->setEmail($registerForm->getValue("email"));

                    $AccountMapper->save($account);

                    $customer = new Application_Model_Customer();
                    $customer->setAccountsId($account->getId());

                    $customer->setSurname($registerForm->getValue('surname'))
                        ->setLastname($registerForm->getValue('lastname'))
                        ->setGender($registerForm->getValue('gender'))
                        ->setTelephone($registerForm->getValue('telephone'));

                    $CustomerMapper->save($customer);

                    $db->commit();
                } catch(Exception $e) {
                    $db->rollBack();
                    throw new Exception($e);
                }

                $this->_helper->flashMessenger->setNamespace("success");
                $this->redirect("/");
            }
        }
        $this->view->registerForm = $registerForm;
    }

    public function registerAction()
    {

    }


}
