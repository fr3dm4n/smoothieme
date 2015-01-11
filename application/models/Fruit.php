<?php

class Application_Model_Fruit {

    protected $ID;
    protected $name;
    protected $color;
    protected $price;
    protected $description;
    protected $kcal;

    /**
     * Standard-Bild
     */
    const DEFAULT_PIC="_default.jpg";

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
    public function getID() {
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
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColor() {
        return $this->color;
    }

    /**
     * @param mixed $color
     * @return $this
     */
    public function setColor($color) {
        $this->color = $color;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * @param mixed $price
     * @return $this
     */
    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return $this
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKcal() {
        return $this->kcal;
    }

    /**
     * @param mixed $kcal
     * @return $this
     */
    public function setKcal($kcal) {
        $this->kcal = $kcal;
        return $this;
    }
    /**
     * Prüft ob ein Bild vorhanden ist und liefert ggf. den Pfad dahin, oder das Default-bild
     */
    public function getPicture(){
        $picturePath=realpath(Zend_Registry::get('config')->backend->fruitpic->path);
        $picturePath.="/".$this->getId().".jpg";

        if(file_exists($picturePath)){
            $picturePath=str_replace($_SERVER['DOCUMENT_ROOT'], '',$picturePath);
            //Absolut-Path?
            $picturePath=$picturePath[0]=="/"?$picturePath:"/".$picturePath;

            return $picturePath;
        }else{
            $picturePath=str_replace($_SERVER['DOCUMENT_ROOT'], '',$picturePath);
            //Absolut-Path?
            $picturePath=$picturePath[0]=="/"?$picturePath:"/".$picturePath;
            return dirname($picturePath)."/".self::DEFAULT_PIC;
        }
    }

    /**
     * Löscht Bild, falls vorhanden
     * @return bool|null NUll, wenn es sich um default-Bild handelt
     * @throws Zend_Exception
     */
    public function deletePicture() {
        $picturePath=realpath(Zend_Registry::get('config')->backend->fruitpic->path);
        $picturePath.="/".$this->getId().".jpg";

        if(file_exists($picturePath)){

           return unlink($picturePath);
        }else{
            return null;
        }
    }

    /**
     * @return mixed
     */
    public function isUsed() {
        $mapper=new Application_Model_FruitMapper();
        return $mapper->isUsed($this);
    }

}

