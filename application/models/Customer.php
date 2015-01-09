<?php

class Application_Model_Customer
{

    protected $customer_id;
    protected $accounts_id;
    protected $surname;
    protected $lastname;
    protected $gender;
    protected $telephone;
    protected $birthdate;

    public function __construct(array $options = null) {
        if (is_array ( $options )) {
            $this->setOptions ( $options );
        }
    }

    public function __set($name, $value) {
        $method = 'set' . $name;
        if (('mapper' == $name) || ! method_exists ( $this, $method )) {
            throw new Exception ( 'Invalid article property' );
        }
        $this->$method ( $value );
    }

    public function __get($name) {
        $method = 'get' . $name;
        if (('mapper' == $name) || ! method_exists ( $this, $method )) {
            throw new Exception ( 'Invalid article property' );
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
     * @return int
     */
    public function getAccountsId()
    {
        return $this->accounts_id;
    }

    /**
     * @param int $accounts_id
     * @return int
     */
    public function setAccountsId($accounts_id)
    {
        if($accounts_id != null && $accounts_id > 0 && is_integer($accounts_id)) {
            $this->accounts_id = $accounts_id;
        }
        else
            echo 'AccountId cannot be null and must be greater than 0!';
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * @param mixed $birthdate
     *
     * @return $this
     */
    public function setBirthdate($birthdate)
    {
        if($birthdate != null){
            $this->birthdate = $birthdate;
        }
        else
            echo 'Birthdate cannot be null';
        return $this;
    }


    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     * @return $this
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    /**
     * @param int $customer_id
     * @return $this
     */
    public function setCustomerId($customer_id)
    {
        if($customer_id != null && is_integer($customer_id)) {
            $this->customer_id = $customer_id;
        }
        else
            echo 'CustomerId cannot be null';
        return $this;
    }



    /**
     * @return String
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param String $lastname
     * @return $this
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return String
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param String $surname
     * @return $this
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @return String
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param String $telephone
     * @return $this
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
        return $this;
    }
}

