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
            'account_id' => $account->getAccountId(),
            'name' => $account->getName (),
            'rolle' => $account->getRole(),
            'passwort' => $account->getPassword(),
        );

        if (null === ($id = $account->getAccountId())) {
            unset ( $data ['id'] );
            $this->getDbTable ()->insert ( $data );
        } else {
            $this->getDbTable ()->update ( $data, array (
                'account_nr = ?' => $id
            ) );
        }
    }

    public function find($id, Application_Model_Account $account) {
        $result = $this->getDbTable ()->find ( $id );
        if (0 == count ( $result )) {
            return;
        }
        $row = $result->current ();
        $account->setAccountId ($row->account_nr)->setName ( $row->name )->setRole ( $row->rolle )->setPassword ( $row->passwort );
    }

    /**
     * Returns a Application_Model_Benutzer with the given login name
     * or null if no result found
     *
     * @param $loginName login name from benutzer
     * @return Application_Model_Benutzer|null
     */
    public function getBenutzerByLoginName($loginName)
    {
        $select = $this->getDbTable()->select()->where('login_name = ?', $loginName);
        $row = $this->getDbTable()->fetchRow($select);

        if ($row === null) {
            return null;
        }

        $user = new Application_Model_Account();
        $user->setAccountId($row->account_id)
            ->setRole($row->Rolle)
            ->setName($row->Benutzername)
            ->setPassword($row->Passwort);

        return $user;
    }

}

