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
     * @return array
     */
    public function getAmountPerSmoothie($smoothieID)
    {
        $fruits = array();
        $fruitEntry=[];
        while ($this->valid()) {
            $fruit = $this->current();
            $fruitEntry["fruit"]= new Application_Model_Fruit($fruit->toArray());
            //Hole Abhängigkeiten aus Kreuztabelle
            $intersectionTable= new Application_Model_SmoothieHasFruitsMapper();
            $amount=$intersectionTable->getAmount($smoothieID,$fruitEntry["fruit"]->getID());
            $fruitEntry["amount"]=intval($amount);
            $fruits[]=$fruitEntry;

            $this->next();
        }

        $this->rewind();

        return $fruits;
    }
}