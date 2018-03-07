<?php

class Ticket {
    
    private $id, $date, $idmember, $idclient;
    
    function __construct($id = null, $date = null, $idmember = null, $idclient = null) {
        $this->id = $id;
        $this->date = $date;
        $this->idmember = $idmember;
        $this->idclient = $idclient;
    }
    
    /*getter & setter generator http://www.kjetil-hartveit.com/blog/1/setter-and-getter-generator-for-php-javascript-c%2B%2B-and-csharp*/
    
    function setId($id) { $this->id = $id; }
    function getId() { return $this->id; }
    function setDate($date) { $this->date = $date; }
    function getDate() { return $this->date; }
    function setIdmember($idmember) { $this->idmember = $idmember; }
    function getIdmember() { return $this->idmember; }
    function setIdclient($idclient) { $this->idclient = $idclient; }
    function getIdclient() { return $this->idclient; }
    
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