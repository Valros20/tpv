<?php

class Line {
    
    private $id, $item, $amount;
    
    function __construct($id, $item = null, $amount = 1) {
        $this->id = $id;
        $this->item = $item;
        $this->amount = $amount;
    }
    function setId($id) {
        $this->id = $id;
    }
    function getId() { 
        return $this->id;
    }
    function setItem($item) {
        $this->item = $item;
    }
    function getItem() {
        return $this->item;
    }
    function setAmount($amount) {
        $this->amount = $amount;
    }
    function getAmount() {
        return $this->amount;
    }
    
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