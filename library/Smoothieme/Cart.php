<?php

/**
 * Warenkorb Klasse
 */
class Smoothieme_Cart {
    /**
     * Artikel des Warenkorbs
     *
     * @var array
     */
    private $articles;


    /**
     * Konstruktor
     *
     * Initialisiert Objekt mit leerem Warenkorbarray.
     */
    function __construct() {
        $this->articles = [];

        $this->session = new Zend_Session_Namespace("Cart");

        //prepare Cart
        if (!isset($this->session->cart)) {
            $this->session->cart = serialize($this->articles);
        } else {
            $this->articles = unserialize($this->session->cart);
        }
    }

    /**
     * Dekonstruktor
     *
     * Wird Klasse entladen, sichere Dateistand zurück in Klasse
     * setzt voraus, dass Warenkorb in der Registry liegt!
     */
    function __destruct(){
        //save back
        $this->session->cart = serialize($this->articles);
    }

    /**
     * Artikel dem Warenkorb hinzufuegen
     *
     * @param $key string|int  Artikel-Identifier
     * @param $amount   int     Optionaler Parameter. Wird eine Menge angegeben, so wird der
     *      Artikel n-fach in den Warenkorb gelegt
     * @return void
     */
    public function addItem($key, $amount = 1) {
        if ($amount < 1) {
            return;
        }
        if (is_string($key) || is_int($key)) {
            if(isset($this->articles[$key])){
                $this->articles[$key] += $amount;
            }else{
                $this->articles[$key] = $amount;
            }
        }
    }

    /**
     * Artikel aus dem Warenkorb entfernen.
     *
     * Es soll keinen Artikel mit Menge 0 oder kleiner im Warenkorb geben. In solch einem
     * Fall ist der Artikel aus dem Warenkorb zu nehmen.
     *
     * @param $key string|int Artikel-Identifier
     * @param $amount   int     Optionaler Parameter. Wird eine Menge angegeben, so wird der
     *      Artikel n-fach aus dem Warenkorb entfernt
     * @return void
     */
    public function removeItem($key, $amount = 1) {
        if (!is_string($key) && !is_int($key) || $amount < 1 || !isset($this->articles[$key])) {
            return;
        }

        $this->articles[$key] -= $amount;
        if ($this->articles[$key] < 1) {
            unset($this->articles[$key]);
        }

    }

    /**
     * Warenkorb komplett leeren
     *
     * @return void
     */
    public function clear() {
        $this->articles = [];
    }

    /**
     * Pruefung, ob der Warenkorb leer ist oder Artikel enthaelt.
     *
     * @return boolean <code>true</code> wenn leer, sonst <code>false</code>
     */
    public function isEmpty() {
        return count($this->articles) == 0;
    }

    /**
     * Pruefung, ob item mit angegebenem Schluessel im Warenkorb liegt
     *
     * @param $key  string|int Schluessel
     * @return mixed  <code>false</code>, wenn nicht enthalten, sonst die enthaltene Menge als Ganzzahl (integer)
     */
    public function containsItem($key) {
        if (is_string($key) || is_int($key)) {
            return isset($this->articles[$key]) ? $this->articles[$key] : false;
        } else {
            return false;
        }
    }

    /**
     * Liefert Inhalte des Warenkorbs als Array mit Schluessel-Wert Paaren.
     *
     * @return array    Inhalte des Warenkorbs
     */
    public function getCartContents() {
        return $this->articles;
    }
}
