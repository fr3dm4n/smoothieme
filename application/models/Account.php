<?php

class Application_Model_Account
{
    protected $id;
    protected $role;
    protected $name;
    protected $password;
    protected $salt;
    protected $email;


    public function __construct(array $options = null) {
        if (is_array ( $options )) {
            $this->setOptions ( $options );
        }
    }

    public function __set($name, $value) {
        $method = 'set' . $name;
        if (('mapper' == $name) || ! method_exists ( $this, $method )) {
            throw new Exception ( 'Invalid account property' );
        }
        $this->$method ( $value );
    }

    public function __get($name) {
        $method = 'get' . $name;
        if (('mapper' == $name) || ! method_exists ( $this, $method )) {
            throw new Exception ( 'Invalid account property' );
        }
        return $this->$method ();
    }

    public function setOptions(array $options) {
        $methods = get_class_methods ( $this );
        foreach ( $options as $key => $value ) {
            $method = 'set' . ucfirst ( $key );
            if (in_array ( $method, $methods )) {
                $this->$method ( $value );
            }
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param mixed $salt
     * @return $this
     */
    public function setSalt($salt)
    {

        if($salt != null) {
            $this->salt = $salt;
        }
        else
            $this->salt = 'saltsaltsalt';
        return $this;
    }

    

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $account_id
     * @return $this
     * @throws Exception
     */
    public function setId($account_id)
    {
        if($account_id > 1 && $account_id != null && is_numeric($account_id)) {
            $this->id = $account_id;
        }
        else
            throw new Exception('AccountID must be greater 0 or notNull!');
        return $this;
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param String $name
     * @return $this
     * @throws Exception
     */
    public function setName($name)
    {
        if($name != null && is_string($name)) {
            $this->name = $name;
        }
        else
            throw new Exception( 'Name must be varchar and notNull!');
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return $this
     * @throws Exception
     */
    public function setPassword($password)
    {
        if($password != null) {
            $this->password = $password;
        }
        else
            throw new Exception( 'Password must be set!');
        return $this;
    }

    /**
     * @return String
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param String $role
     * @return $this
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return String
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param String $email
     * @return $this
     * @throws Exception
     */
    public function setEmail($email)
    {
        if($email != null && is_string($email)) {
            $this->email = $email;
        }
        else
            throw new Exception( 'Email cannot be null');
        return $this;
    }


}

