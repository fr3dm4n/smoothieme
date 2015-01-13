<?php


class Application_Model_DbTable_SmoothieHasOrder extends Zend_Db_Table_Abstract
{
    protected $_name = 'smoothies_has_orders';
    protected $_referenceMap = array(
        'Smoothie' => array(
            'columns' => 'smoothies_ID',
            'refTableClass' => 'Application_Model_DbTable_Smoothie',
            'refColumns' => 'ID'
        ),
        'Order' => array(
            'columns' => 'order_ID',
            'refTableClass' => 'Application_Model_DbTable_Order',
            'refColumns' => 'ID'
        ),


    );

}

