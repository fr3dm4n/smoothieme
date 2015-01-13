<?php

class Application_Model_SmoothieHasOrdersMapper
{
    private $_dbTable;

    /**
     * Speichert/Ändert Smoothie
     * @param Application_Model_Order $order
     * @return mixed
     */
    public function save(Application_Model_Order $order) {
        $orderID=$order->getID();
        $smoothies=$order->getSmoothies();

        foreach($smoothies as $smoothie){
            $data = array(
                'orders_ID' => $orderID,
                'smoothies_ID' => $smoothie['smoothie']->getID(),
                'count' => $smoothie['count'],
            );
            $this->getDbTable()->insert($data);

        }
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
            $this->setDbTable('Application_Model_DbTable_SmoothieHasOrder');
        }
        return $this->_dbTable;
    }

}

