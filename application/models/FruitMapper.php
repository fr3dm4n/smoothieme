<?php

class Application_Model_FruitMapper
{
    private $_dbTable;


    /**
     * Speichert/Ändert Frucht
     * @param Application_Model_Fruit $fruit
     */
    public function save(Application_Model_Fruit $fruit) {
        $data = array(
            'name' => $fruit->getName(),
            'description' => $fruit->getDescription(),
            'price' => $fruit->getPrice(),
            'color' => $fruit->getColor(),
            'kcal' => $fruit->getKcal()
        );

        $id=$fruit->getID();

        if (is_null($id)) {
            return $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('ID = ?' => $id));
        }
    }

    /**
     * Select Frucht
     * @param                          $id
     * @param Application_Model_Fruit $fruit
     */
    public function find($id, Application_Model_Fruit $fruit) {
        $result = $this->getDbTable()->find($id);
        if (count($result)==0) {
            return;
        }
        $row = $result->current();

        $fruit->setID($row->ID)
            ->setName($row->name)
            ->setColor($row->color)
            ->setDescription($row->description)
            ->setPrice($row->price)
            ->setKcal($row->kcal);
    }

    /**
     * Gibt alle Früchte aus
     * @return array
     */
    public function fetchAll() {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries = array();
        foreach ($resultSet as $row) {
            $fruit = new Application_Model_Fruit();
            $fruit->setID($row->ID)
                ->setName($row->name)
                ->setColor($row->color)
                ->setDescription($row->description)
                ->setPrice($row->price)
                ->setKcal($row->kcal);
            $entries[] = $fruit;
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
            $this->setDbTable('Application_Model_DbTable_Fruit');
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

    /**
     * Prüft pob Frucht verwendet wird
     * @param Application_Model_Fruit $fruit
     */
    public function isUsed(Application_Model_Fruit $fruit) {
//        $this->find($fruit->getID(),$f)


    }


}

