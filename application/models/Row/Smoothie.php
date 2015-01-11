<?php

/**
 * User: Alfred Feldmeyer
 * Date: 11.01.2015
 * Time: 00:11
 * Copyright by Webtaurus. Alle Rechte vorbehalten!
 */
class Application_Model_Row_Smoothie extends Zend_Db_Table_Row_Abstract {
    private $fruits = null;
    private $customer=null;

    /**
     * Liefert die Früchte zu einem Smoothie
     * @return Application_Model_Rowset_Fruits
     */
    public function getFruits() {
        if (!$this->fruits) {
            $this->fruits = $this->findManyToManyRowset('Application_Model_DbTable_Fruit',   // match table
                'Application_Model_DbTable_SmoothieHasFruits');  // join table
        }
        return $this->fruits;
    }

    /**
     * Liefert den Kunden zu einem Smoothie, falls vorhanden
     */
    public function getCustomer(){
        if (!$this->customer) {
            $this->customer = $this->findParentRow('Application_Model_DbTable_Customer');
        }
        return $this->customer;
    }

}