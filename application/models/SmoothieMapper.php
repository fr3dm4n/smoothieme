<?php

class Application_Model_SmoothieMapper
{

    private $_dbTable;


    /**
     * Speichert/Ändert Artikel
     * @param Application_Model_Smoothie $smoothie
     * @return mixed
     */
    public function save(Application_Model_Smoothie $smoothie) {
        $customerID=is_null($smoothie->getCustomer())?null:$smoothie->getCustomer()->getId();
        $data = array(
            'name' => $smoothie->getName(),
            'size' => $smoothie->getSize(),
            'customer_ID' => $customerID
        );
        if(count($smoothie->getFruits())<1){
            throw new InvalidArgumentException("Smoothies can not be saved without fruits");
        }
        $id=$smoothie->getID();


        if (is_null($id)) {
            $smoothieID=$this->getDbTable()->insert($data);
            $smoothie->setID($smoothieID);

        } else {
            $this->getDbTable()->update($data, array('ID = ?' => $id));
        }

        $smoothieHasFruits=new Application_Model_SmoothieHasFruitsMapper();
        $smoothieHasFruits->save($smoothie);

        return $smoothie->getID();
    }

    /**
     * Select Smoothie
     * @param                          $id
     * @param Application_Model_Smoothie $smoothie
     * @return void|Zend_Db_Table_Rowset_Abstract
     */
    public function find($id, Application_Model_Smoothie $smoothie) {
        $result = $this->getDbTable()->find($id);
        if (count($result)==0) {
            return;
        }
        $row = $result->current();

        $smoothie->setId($row->ID)
            ->setName($row->name)
            ->setSize($row->size);

        //Füge Früchte ein
        $fruits=$result->current()->getFruits()->getAmountPerSmoothie($row->ID);

        foreach($fruits as $fruit){
            $smoothie->addFruits($fruit["amount"],$fruit["fruit"]);
        }

        //Füge Customer ein
        $customerRow=$result->current()->getCustomer();
        if(!is_null($customerRow)){
            $customer=new Application_Model_Customer($customerRow->toArray());
            $smoothie->setCustomer($customer);
        }

        return $result;
    }

    /**
     * Gibt alle Smoothies aus
     * @return array
     */
    public function fetchAll() {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries = array();
        foreach ($resultSet as $row) {
            $smoothie = new Application_Model_Smoothie();
            $smoothie->setId($row->ID)
                ->setName($row->name)
                ->setSize($row->size);

            //Füge Früchte ein
            $fruits=$resultSet->current()->getFruits()->getAmountPerSmoothie($row->ID);

            foreach($fruits as $fruit){
                $smoothie->addFruits($fruit["amount"],$fruit["fruit"]);
            }

            //Füge Customer ein
            $customerRow=$resultSet->current()->getCustomer();
            if(!is_null($customerRow)){
                $customer=new Application_Model_Customer($customerRow->toArray());
                $smoothie->setCustomer($customer);
            }

            $entries[] = $smoothie;
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
     * @return Zend_Db_Table_Abstract
     * @throws Exception
     */
    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Smoothie');
        }
        return $this->_dbTable;
    }

    /**
     * Löscht eine Frucht
     * @param $id
     * @return int
     */
    public function delete($id) {
        return $this->getDbTable()->delete(array('ID = ?' => $id));
    }

}

