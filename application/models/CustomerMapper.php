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
            'customer_id' => $customer->getCustomerId(),
            'accounts_id' => $customer->getAccountsId(),
            'surname' => $customer->getSurname(),
            'lastname' => $customer->getLastname(),
            'gender' => $customer->getGender(),
            'tel' => $customer->getTelephone(),
            'birthdate' => $customer->getBirthdate(),
        );

        if (null === ($id = $customer->getCustomerId())) {
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
        $customer->setCustomerId ( $row->kundennr )->setAccountsId($row->account_nr)
            ->setSurname( $row->vorname )->setLastname ( $row->nachname)->setGender( $row->Geschlecht )
            -> setTelephone($row->Telefon)->setBirthdate($row->Geburtsdatum);
    }


}

