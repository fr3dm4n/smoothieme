<?php

class Application_Model_DbTable_Fruit extends Zend_Db_Table_Abstract
{

    protected $_name = 'fruits';
    protected $_primary = 'ID';
    protected $_rowClass = 'Application_Model_Row_Fruit';
    protected $_rowsetClass = 'Application_Model_Rowset_Fruits';
    protected $_dependentTables = array('Application_Model_DbTable_SmoothieHasFruits');
}

