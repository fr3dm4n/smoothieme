<?php

class Application_Model_SmoothieHasFruitsMapper
{
    private $_dbTable;

    /**
     * Speichert/Ändert Frucht
     * @param Application_Model_Smoothie $smoothie
     * @return mixed
     */
    public function save(Application_Model_Smoothie $smoothie) {
        $smoothieID=$smoothie->getID();
        $fruits=$smoothie->getFruits();

        foreach($fruits as $fruitData){
            $data = array(
                'smoothie_ID' => $smoothieID,
                'fruit_ID' => $fruitData["fruit"]->getID(),
                'ml' => $fruitData["amount"],
            );

//            if (is_null($smoothieID)) {
                 $this->getDbTable()->insert($data);
//            } else {
//                $this->getDbTable()->update($data, array('smoothie_ID = ?' => $smoothieID,'fruit_ID = ?'=>$fruitData["fruit"]->getID()));
//            }
        }
    }

    /**
     * Liefert die Anzahl an ml ja verwendeter Frucht
     * @param $smoothieID
     * @param $fruitID
     */
    public function getAmount($smoothieID,$fruitID){
        $result=$this->getDbTable()->find($smoothieID,$fruitID);

        if (count($result)==0) {
            return;
        }
        $row = $result->current();
        return $row->toArray()["ml"];
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
            $this->setDbTable('Application_Model_DbTable_SmoothieHasFruits');
        }
        return $this->_dbTable;
    }

}

