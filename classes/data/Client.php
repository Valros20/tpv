<?php

class Client {
    
    use Comun;
    
    private $id, $name, $surname, $tin, $address, $location, $postalcode, $province, $email;
    
    function __construct($id = null, $name = null, $surname = null, $tin = null, $address = null, $location = null, $postalcode = null, $province = null, $email = null) {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->tin = $tin;
        $this->address = $address;
        $this->location = $location;
        $this->postalcode = $postalcode;
        $this->province = $province;
        $this->email = $email;
    }
    
    /*getter & setter generator http://www.kjetil-hartveit.com/blog/1/setter-and-getter-generator-for-php-javascript-c%2B%2B-and-csharp*/
    
    function setId($id) { $this->id = $id; }
    function getId() { return $this->id; }
    function setName($name) { $this->name = $name; }
    function getName() { return $this->name; }
    function setSurname($surname) { $this->surname = $surname; }
    function getSurname() { return $this->surname; }
    function setTin($tin) { $this->tin = $tin; }
    function getTin() { return $this->tin; }
    function setAddress($address) { $this->address = $address; }
    function getAddress() { return $this->address; }
    function setLocation($location) { $this->location = $location; }
    function getLocation() { return $this->location; }
    function setPostalcode($postalcode) { $this->postalcode = $postalcode; }
    function getPostalcode() { return $this->postalcode; }
    function setProvince($province) { $this->province = $province; }
    function getProvince() { return $this->province; }
    function setEmail($email) { $this->email = $email; }
    function getEmail() { return $this->email; }
}