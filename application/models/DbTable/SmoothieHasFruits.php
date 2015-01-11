<?php

class Application_Model_DbTable_SmoothieHasFruits extends Zend_Db_Table_Abstract
{
    protected $_name = 'smoothies_has_fruits';
    protected $_referenceMap = array(
        'Smoothie' => array(
            'columns' => 'smoothie_ID',
            'refTableClass' => 'Application_Model_DbTable_Smoothie',
            'refColumns' => 'ID'
        ),
        'Fruits' => array(
            'columns' => 'fruit_ID',
            'refTableClass' => 'Application_Model_DbTable_Fruit',
            'refColumns' => 'ID'
        ),

    );

}

