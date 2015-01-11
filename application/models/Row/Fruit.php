<?php

/**
 * Created by Alfred Feldmeyer
 * User: Alfred Feldmeyer
 */
class Application_Model_Row_Fruit extends Zend_Db_Table_Row_Abstract {

    /**
     * Wird Frucht verwendet?
     * @return bool
     */
    public function isUsed() {
        return $smoothies=$this->findDependentRowset('Application_Model_DbTable_SmoothieHasFruits')->count()>0;
    }
}