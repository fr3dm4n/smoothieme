<?php

class Application_Model_AccountMapper
{

    protected $_dbTable;

    public function setDbTable($dbTable) {
        if (is_string ( $dbTable )) {
            $dbTable = new $dbTable ();
        }
        if (! $dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception ( 'Invalid table data gateway provided' );
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable ( 'Application_Model_DbTable_Account' );
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_Account $account) {
        $data = array (
            'id' => $account->getId(),
            'name' => $account->getName (),
            'role' => $account->getRole(),
            'password' => $account->getPassword(),
            'salt'=> $account->getSalt(),
            'email'=>$account->getEmail()
        );

        if (null === ($id = $account->getId())) {
            unset ( $data ['id'] );
            $id = $this->getDbTable ()->insert ( $data );
            $account->setId($id);
        } else {
            $this->getDbTable ()->update ( $data, array (
                'ID = ?' => $id
            ) );
        }
    }

    public function find($id, Application_Model_Account $account) {
        $result = $this->getDbTable ()->find ( $id );
        if (0 == count ( $result )) {
            return;
        }
        $row = $result->current ();

        $account->setId($row->ID)
            ->setName ( $row->name )
            ->setRole ( $row->role )
            ->setPassword ( $row->password )
            ->setSalt($row->salt)
            ->setEmail($row->email);
    }

    /**
     * Returns a Application_Model_Account with the given login name
     * or null if no result found
     *
     * @param $loginName login name from user
     * @return Application_Model_Account|null
     */
    public function getUserByLoginName($loginName)
    {
        $select = $this->getDbTable()->select()->where('name = ?', $loginName);
        $row = $this->getDbTable()->fetchRow($select);

        if ($row === null) {
            return null;
        }

        $user = new Application_Model_Account();
        $user->setId($row->ID)
            ->setRole($row->role)
            ->setName($row->name)
            ->setPassword($row->password)
            ->setSalt($row->salt)
            ->setEmail($row->email);

        return $user;
    }

}

