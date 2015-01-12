<?php

class Application_Model_AddressMapper
{
    private $_dbTable;


    /**
     * Speichert/Ändert Artikel
     * @param Application_Model_Address $address
     */
    public function save(Application_Model_Address $address) {
        $data = array(
            'street' => $address->getStreet(),
            'number' => $address->getNumber(),
            'zip' => $address->getZip(),
            'country' => $address->getCountry(),
        );

        $id=$address->getId();

        if (is_null($id)) {
            return $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('ID = ?' => $id));
        }
    }

    /**
     * Select Artikel
     * @param                           $id
     * @param Application_Model_Address $address
     */
    public function find($id, Application_Model_Address $address) {
        $result = $this->getDbTable()->find($id);
        if (count($result)==0) {
            return;
        }
        $row = $result->current();

        $address->setId($row->ID)
            ->setStreet($row->street)
            ->setNumber($row->number)
            ->setZip($row->zip)
            ->setCountry($row->country);
    }

    /**
     * Gibt alle Artikel aus
     * @return array
     */
    public function fetchAll() {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries = array();
        foreach ($resultSet as $row) {
            $address = new Application_Model_Fruit();
            $address->setId($row->ID)
                ->setStreet($row->street)
                ->setNumber($row->number)
                ->setZip($row->zip)
                ->setCountry($row->country);
            $entries[] = $address;
        }
        return $entries;
    }

    /**
     * Setzt Tabelle
     * @param $dbTable
     * @return $this
     * @throws Exception
     */
    public function setDbTable($dbTable) {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Ungültiges Table Data Gateway angegeben');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    /**
     * Liefert Tabelle
     * @return mixed
     * @throws Exception
     */
    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Address');
        }
        return $this->_dbTable;
    }

    /**
     * Löscht eine Frucht
     * @param $id
     */
    public function delete($id) {
        return $this->getDbTable()->delete(array('ID = ?' => $id));
    }


}