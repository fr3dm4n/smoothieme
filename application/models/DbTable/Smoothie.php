<?php

class Application_Model_DbTable_Smoothie extends Zend_Db_Table_Abstract
{
    protected $_name = 'smoothies';
    protected $_primary = 'ID';

    protected $_rowClass = 'Application_Model_Row_Smoothie';
    protected $_dependentTables = array('Application_Model_DbTable_SmoothieHasFruits','Application_Model_DbTable_Customer');
    protected $_referenceMap = array(
        'Smoothie' => array(
            'columns'       => 'customer_ID',
            'refTableClass' => 'Application_Model_DbTable_Customer',
            'refColumns'    => 'ID'
        )
    );
}

