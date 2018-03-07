<?php

class Cart {
    
    private $cart = [];
    
    function __construct() {
        
    }
    function addLine(Line $line) {
        if((isset($this->cart[$line->getId()]))) {
            $prevLine = $this->cart[$line->getId()];
            $prevLine->setAmount($prevLine->getAmount() + $line->getAmount());
            $this->cart[$prevLine->getId()] = $prevLine;
        } else {
            $this->cart[$line->getId()] = $line;
        }
    }
    function add($id, $product = null, $amount = 1) {
        $this->addLine(new Line($id, $product, $amount));
    }
    function substractLine(Line $line) {
        if((isset($this->cart[$line->getId()]))) {
            $prevLine = $this->cart[$line->getId()];
            $prevLine->setAmount($prevLine->getAmount() - $line->getAmount());
            if($prevLine->getAmount() < 1) {
                $this->removeLine($line);
            } else {
                $this->cart[$prevLine->getId()] = $prevLine;
            }
        }
    }
    function substract($id) {
        $this->substractLine(new Line($id));
    }
    function removeLine(Line $line) {
        unset($this->cart[$line->getId()]);
    }
    function remove($id) {
        $this->removeLine(new Line($id));
    }
    function getCart() {
        return $this->cart;
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