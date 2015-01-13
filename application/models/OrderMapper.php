<?php

class Application_Model_OrderMapper
{
    private $_dbTable;


    /**
     * Speichert/Ändert Orders
     * @param Application_Model_Order $order
     */
    public function save(Application_Model_Order $order) {
        $data = array(
            'delivery_address' => $order->getDeliveryAddress(),
            'invoice_address' => $order->getInvoiceAddress(),
            'delivery_method' => $order->getDeliveryMethod(),
            'payment_method' => $order->getPaymentMethod(),
            'account_ID' => $order->getAccountId()
        );

        $smoothies = $order->getSmoothies();

        if (count($smoothies) < 1){
            throw new InvalidArgumentException("Order can not be saved without Smoothies");
        }

        $id=$order->getID();

        if (is_null($id)) {
            $id = $this->getDbTable()->insert($data);
            $order->setID($id);
        } else {
            $this->getDbTable()->update($data, array('ID = ?' => $id));
        }

        $smoothieHasOrderMapper = new Application_Model_SmoothieHasOrdersMapper();
        $smoothieHasOrderMapper->save($order);
    }

    /**
     * Select Order
     * @param                          $id
     * @param Application_Model_Order $order
     * @return bool
     */
    public function find($id, Application_Model_Order $order) {
        $result = $this->getDbTable()->find($id);
        if (count($result)==0) {
            return false;
        }
        $row = $result->current();

        $order->setID($row->ID)
            ->setDeliveryAddress($row->delivery_address)
            ->setInvoiceAddress($row->invoice_address)
            ->setDeliveryMethod($row->delivery_method)
            ->setPaymentMethod($row->payment_method)
            ->setAccountId($row->account_ID);
    }

    /**
 * Gibt alle Orders aus
 * @return array
 */
    public function fetchAll() {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries = array();
        foreach ($resultSet as $row) {
            $order = new Application_Model_Order();
            $order->setDeliveryAddress($row->delivery_address)
                ->setInvoiceAddress($row->invoice_address)
                ->setDeliveryMethod($row->delivery_method)
                ->setPaymentMethod($row->payment_method)
                ->setAccountId($row->account_ID);
            $entries[] = $order;
        }
        return $entries;
    }

    /**
     * Gibt alle Orders des Kunden aus
     * @return array
     * @param $id
     */
    public function showOrdersByAccId($id) {
        $resultSet = $this->getDbTable()->fetchAll();
        $this->getDbTable()->fetchAll(array('account_ID' => $id));
        $entries = array();
        foreach ($resultSet as $row) {
            $order = new Application_Model_Order();
            $order->setDeliveryAddress($row->delivery_address)
                ->setInvoiceAddress($row->invoice_address)
                ->setDeliveryMethod($row->delivery_method)
                ->setPaymentMethod($row->payment_method)
                ->setAccountId($row->account_ID);
            $entries[] = $order;
        }
        return $entries;
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
            $this->setDbTable('Application_Model_DbTable_Order');
        }
        return $this->_dbTable;
    }

    /**
     * Löscht einen Order
     * @param $id
     * @return bool
     */
    public function delete($id) {
        return $this->getDbTable()->delete(array('ID = ?' => $id));
    }




}

