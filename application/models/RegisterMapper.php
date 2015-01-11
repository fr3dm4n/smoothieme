<?php

class Application_Model_RegisterMapper
{
    private $_dbTable;


    /**
     * Speichert/Ändert Artikel
     * @param Application_Model_Register $register
     */
    public function save(Application_Model_Register $register) {
        $data = array(
            'surname' => $register->getSurname(),
            'lastname' => $register->getLastname(),
            'email' => $register->getEmail(),
            'gender' => $register->getGender(),
            'password' => $register->getPassword(),
            'password2' => $register->getPassword2(),
            'captcha' => $register->getCaptcha()
        );

        $id=$register->getId();

        if (is_null($id)) {
            return $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('ID = ?' => $id));
        }
    }

    /**
     * Select Artikel
     * @param                            $id
     * @param Application_Model_Register $register
     */
    public function find($id, Application_Model_Register $register) {
        $result = $this->getDbTable()->find($id);
        if (count($result)==0) {
            return;
        }
        $row = $result->current();

        $register->setId($row->ID)
            ->setSurname($row->surname)
            ->setLastname($row->lastname)
            ->setEmail($row->email)
            ->setGender($row->gender)
            ->setPassword($row->password)
            ->setPassword2($row->password2)
            ->setCaptcha($row->captacha);
    }

    /**
     * Gibt alle Artikel aus
     * @return array
     */
    public function fetchAll() {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries = array();
        foreach ($resultSet as $row) {
            $register = new Application_Model_Register();
            $register->setId($row->ID)
                ->setSurname($row->surname)
                ->setLastname($row->lastname)
                ->setEmail($row->email)
                ->setGender($row->gender)
                ->setPassword($row->password)
                ->setPassword2($row->password2)
                ->setCaptcha($row->captacha);
            $entries[] = $register;
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
     * @return mixed
     * @throws Exception
     */
    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Register');
        }
        return $this->_dbTable;
    }
}

