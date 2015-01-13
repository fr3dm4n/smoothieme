<?php

class Application_Model_Order
{
    protected $ID;
    protected $delivery_address;
    protected $invoice_address;
    protected $delivery_method;
    protected $payment_method;
    protected $accountID;
    protected $smoothies = array();


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
    public function getID()
    {
        return $this->ID;
    }

    /**
     * @param int $ID
     * @return Application_Model_Order
     */
    public function setID($ID)
    {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeliveryAddress()
    {
        return $this->delivery_address;
    }

    /**
     * @param mixed $delivery_address
     * @return Application_Model_Order
     */
    public function setDeliveryAddress($delivery_address)
    {
        $this->delivery_address = $delivery_address;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInvoiceAddress()
    {
        return $this->invoice_address;
    }

    /**
     * @param mixed $invoice_address
     * @return Application_Model_Order
     */
    public function setInvoiceAddress($invoice_address)
    {
        $this->invoice_address = $invoice_address;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeliveryMethod()
    {
        return $this->delivery_method;
    }

    /**
     * @param mixed $delivery_method
     * @return Application_Model_Order
     */
    public function setDeliveryMethod($delivery_method)
    {
        $this->delivery_method = $delivery_method;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentMethod()
    {
        return $this->payment_method;
    }

    /**
     * @param mixed $payment_method
     * @return Application_Model_Order
     */
    public function setPaymentMethod($payment_method)
    {
        $this->payment_method = $payment_method;
        return $this;
    }

    /**
     * @param Application_Model_Smoothie $s
     * @return $this
     */
    public function addSmoothie($count, Application_Model_Smoothie $s)
    {
        $this->smoothies[] = array(
            'smoothie' => $s,
            'count'  => $count
        );

        return $this;
    }

    /**
     * @return array
     */
    public function getSmoothies()
    {
        return $this->smoothies;
    }

    /**
     * @param $accountID
     * @return Application_Model_Order
     */
    public function setAccountId($accountID)
    {
        $this->accountID = $accountID;
        return $this;
    }

    /**
     * @return Application_Model_Account
     */
    public function getAccountId()
    {
        return $this->accountID;
    }
}

