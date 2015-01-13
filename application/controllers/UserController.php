<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $user = Zend_Auth::getInstance()->getStorage()->read();
            $id = $user->ID;

            $customermodel = new Application_Model_Customer();


            $userForm = new Application_Form_User();

            $mapper = new Application_Model_CustomerMapper();

            $customer = $mapper->getCustomerByAccId($id);
            $username = $user->name;
            $surname = $customer->getSurname();
            $lastname = $customer->getLastname();
            $email = $user->email;
            $telephone = $customer->getTelephone();

            $userForm->setDefault('username', $username)->setDefault('surname', $surname)->setDefault('lastname', $lastname)
                ->setDefault('email', $email)->setDefault('telephone', $telephone);

            $this->view->userForm = $userForm;
        } else
            $this->redirect("/");
    }

    public function changeAction()
    {
        $user = Zend_Auth::getInstance()->getStorage()->read();
        $id = $user->ID;

        $CustomerMapper = new Application_Model_CustomerMapper();
        $AccountMapper = new Application_Model_AccountMapper();

        $account = new Application_Model_Account();
        $AccountMapper->find($user->ID, $account);

        $customer = $CustomerMapper->getCustomerByAccId($account->getId());

        $userForm = new Application_Form_User();
        $req = $this->getRequest();

        if ($req->isPost()) {

            if ($userForm->isValid($req->getPost())) {

                $db = Zend_Db_Table_Abstract::getDefaultAdapter();
                try {
                    $db->beginTransaction();

                    $account->setEmail($userForm->getValue("email"));

                    $AccountMapper->save($account);

                    $customer->setSurname($userForm->getValue('surname'))
                        ->setLastname($userForm->getValue('lastname'))
                        ->setTelephone($userForm->getValue('telephone'));


                    $CustomerMapper->save($customer);

                    $db->commit();
                } catch (Exception $e) {
                    $db->rollBack();
                    throw new Exception($e);
                }

            }

        }

        $this->view->userForm = $userForm;

    }
}



