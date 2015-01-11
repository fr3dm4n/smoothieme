<?php
/**
 * Created by Webtaurus.
 * User: Alfred Feldmeyer
 * Date: 11.01.2015
 * Time: 00:14
 * Copyright by Webtaurus. Alle Rechte vorbehalten!
 */

class Application_Model_Rowset_Fruits extends Zend_Db_Table_Rowset_Abstract {
    /**
     * @return array the tags in an array
     */
    public function getAsArray()
    {
        $fruits = array();
        $fruitEntry=[];
        while ($this->valid()) {
            $fruit = $this->current();
            $fruitEntry["fruit"]= new Application_Model_Fruit($fruit->toArray());
            //Hole Abhängigkeiten aus Kreuztabelle
            $amountRelation=$fruit->findDependentRowset('Application_Model_DbTable_SmoothieHasFruits',"Fruits")->toArray()[0];
            $fruitEntry["amount"]=intval($amountRelation["ml"]);
            $fruits[]=$fruitEntry;

            $this->next();
        }

        $this->rewind();

        return $fruits;
    }
}