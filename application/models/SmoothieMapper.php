<?php

class Application_Model_SmoothieMapper
{

    private $_dbTable;


//    /**
//     * Speichert/Ändert Artikel
//     * @param Application_Model_Smoothie $smoothie
//     */
//    public function save(Application_Model_Smoothie $smoothie) {
//        $data = array(
//            'name' => $smoothie->getName(),
//            'description' => $smoothie->getDescription(),
//            'price' => $smoothie->getPrice(),
//            'color' => $smoothie->getColor(),
//            'kcal' => $smoothie->getKcal()
//        );
//
//        $id=$smoothie->getId();
//
//        if (is_null($id)) {
//            return $this->getDbTable()->insert($data);
//        } else {
//            $this->getDbTable()->update($data, array('ID = ?' => $id));
//        }
//    }

    /**
     * Select Smoothie
     * @param                          $id
     * @param Application_Model_Smoothie $smoothie
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
            $fruits=$resultSet->current()->getFruits()->getAsArray();

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
     * @return mixed
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
     */
    public function delete($id) {
        return $this->getDbTable()->delete(array('ID = ?' => $id));
    }

}

