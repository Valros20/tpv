<?php

class Model {

    private $dataBase;
    private $data;

    function __construct() {
        $this->dataBase = new DataBase();
        $this->data = array();
    }

    function __destruct() {
        $this->dataBase->closeConnection();
    }

    function getDataBase() {
        return $this->dataBase;
    }

    function getData($name) {
        if(isset($this->data[$name])){
            return $this->data[$name];
        }
        return null;
    }

    function getAllData() {
        return $this->data;
    }

    function setData($name, $data) {
        $this->data[$name] = $data;
    }
    
    function setDataFromAssociative(array $array) {
        foreach($array as $key => $value) {
            $this->data[$key] = $value;
        }
    }
}