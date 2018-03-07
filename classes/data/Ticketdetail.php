<?php

class Ticketdetail {
        
    private $id, $idticket, $idproduct, $quantity, $price;
    
    function __construct($id = null, $idticket = null, $idproduct = null, $quantity = null, $price = null) {
        $this->id = $id;
        $this->idticket = $idticket;
        $this->idproduct = $idproduct;
        $this->quantity = $quantity;
        $this->price = $price;
    }
    
    /*getter & setter generator http://www.kjetil-hartveit.com/blog/1/setter-and-getter-generator-for-php-javascript-c%2B%2B-and-csharp*/
    
    function setId($id) { $this->id = $id; }
    function getId() { return $this->id; }
    function setIdticket($idticket) { $this->idticket = $idticket; }
    function getIdticket() { return $this->idticket; }
    function setIdproduct($idproduct) { $this->idproduct = $idproduct; }
    function getIdproduct() { return $this->idproduct; }
    function setQuantity($quantity) { $this->quantity = $quantity; }
    function getQuantity() { return $this->quantity; }
    function setPrice($price) { $this->price = $price; }
    function getPrice() { return $this->price; }
    
    /* común a todas las clases */

    function getAttributes(){
        $atributos = [];
        foreach($this as $atributo => $valor){
            $atributos[] = $atributo;
        }
        return $atributos;
    }

    function getValues(){
        $valores = [];
        foreach($this as $valor){
            $valores[] = $valor;
        }
        return $valores;
    }
    
    
    function getAttributesValues(){
        $valoresCompletos = [];
        foreach($this as $atributo => $valor){
            $valoresCompletos[$atributo] = $valor;
        }
        return $valoresCompletos;
    }
    
    function read(){
        foreach($this as $atributo => $valor){
            $this->$atributo = Request::read($atributo);
        }
    }
    
    function set(array $array, $pos = 0){
        foreach ($this as $campo => $valor) {
            if (isset($array[$pos]) ) {
                $this->$campo = $array[$pos];
            }
            $pos++;
        }
    }
    
    function setFromAssociative(array $array){
        foreach($this as $indice => $valor){
            if(isset($array[$indice])){
                $this->$indice = $array[$indice];
            }
        }
    }
    
    public function __toString() {
        $cadena = get_class() . ': ';
        foreach($this as $atributo => $valor){
            $cadena .= $atributo . ': ' . $valor . ', ';
        }
        return substr($cadena, 0, -2);
    }
}