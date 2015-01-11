<?php

class Application_Model_Smoothie
{
    //Größen in ml
    const SIZE_S="S";
    const SIZE_M="M";
    const SIZE_L="L";
    /**
     * @var array Größen der Smoothies
     */
    public static $sizes=array(
        self::SIZE_S=>"100",
        self::SIZE_M=>"250",
        self::SIZE_L=>"500"
    );

    protected $ID;
    protected $name;
    protected $size;
    protected $fruits=[];
    protected $customer;


    /**
     * @param array $options
     */
    public function __construct(array $options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options) {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method ($value);
            }
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->ID;
    }

    /**
     * @param mixed $id
     * @return $this
     */
    public function setID($id) {
        $this->ID = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return $this
     */
    public function setName($name) {
        if(empty($name)){
            throw new InvalidArgumentException("Invalid smoothie-name passed");
        }
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSize() {
        return $this->size;
    }

    /**
     * @param mixed $size
     * @return $this
     */
    public function setSize($size) {
        if(!in_array($size,[self::SIZE_S,self::SIZE_M,self::SIZE_L])){
            throw new InvalidArgumentException("Invalid smoothie-size passed");
        }
        $this->size = $size;
        return $this;
    }


    public function getPrice(){
        $fruits=$this->getFruits();
        $price=0;
        foreach($fruits as $fruitsData){
            $pricePerMl=$fruitsData["fruit"]->getPrice()/100;
            $price+=$pricePerMl*$fruitsData["amount"];
        }
        return $price;
    }

    /**
     * @return mixed
     */
    public function getFruits() {
        return $this->fruits;
    }

    /**
     * Fügt Früchte hinzu
     * @param int $amount
     * @param Application_Model_Fruit $fruit
     */
    public function addFruits($amount,Application_Model_Fruit $fruit) {
        if(!is_int($amount)){
            throw new InvalidArgumentException("Amount musst be an integer");
        }

        $this->fruits[$fruit->getId()] = ["amount"=>$amount, "fruit"=>$fruit];
    }

    /**
     * Entfernt alle Früchte
     */
    public function clearFruits(){
        $this->fruits=[];
    }

    /**
     * @return Application_Model_Customer
     */
    public function getCustomer() {
        return $this->customer;
    }

    /**
     * @param Application_Model_Customer $customer
     */
    public function setCustomer(Application_Model_Customer $customer) {
        $this->customer = $customer;
    }

}

