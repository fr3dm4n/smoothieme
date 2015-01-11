<?php

class Application_Model_CustomerMapper
{

    protected $_dbTable;

    public function setDbTable($dbTable) {
        if (is_string ( $dbTable )) {
            $dbTable = new $dbTable ();
        }
        if (! $dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception ( 'Invalid table data gateway provided' );
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable ( 'Application_Model_DbTable_Customer' );
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_Customer $customer) {
        $data = array (
            'customer_id' => $customer->getId(),
            'accounts_id' => $customer->getAccountsId(),
            'surname' => $customer->getSurname(),
            'lastname' => $customer->getLastname(),
            'gender' => $customer->getGender(),
            'tel' => $customer->getTelephone(),
            'birthdate' => $customer->getBirthdate(),
        );

        if (null === ($id = $customer->getId())) {
            unset ( $data ['id'] );
            $this->getDbTable ()->insert ( $data );
        } else {
            $this->getDbTable ()->update ( $data, array (
                'kundennr = ?' => $id
            ) );
        }
    }

    public function find($id, Application_Model_Customer $customer) {
        $result = $this->getDbTable ()->find ( $id );
        if (0 == count ( $result )) {
            return;
        }
        $row = $result->current ();
        $customer->setId ( $row->kundennr )->setAccountsId($row->account_nr)
            ->setSurname( $row->vorname )->setLastname ( $row->nachname)->setGender( $row->Geschlecht )
            -> setTelephone($row->Telefon)->setBirthdate($row->Geburtsdatum);
    }

    public function getCustomerByAccId($id){

        $select = $this->getDbTable()->select()->where('accounts_ID = ?', $id);
        $row = $this->getDbTable()->fetchRow($select);

        if ($row === null) {
            return null;
        }

        $user = new Application_Model_Customer();
        $user->setId($row->ID)
            ->setAccountsId($row->accounts_ID)
            ->setSurname($row->surname)
            ->setLastname($row->lastname)
            ->setGender($row->gender)
            ->setTelephone($row->tel)
            ->setBirthdate($row->birthdate);

        return $user;
    }


}

