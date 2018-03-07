<?php

class User {

    private $id, $name, $surnames, $username, $email, $pass, $role, $registerDate, $verified;
    
    function __construct($id = null, $name = null, $surnames = null, $username = null, $email = null, $pass = null, $role = null, $registerDate = null, $verified = null) {
        $this->id = $id;
        $this->name = $name;
        $this->surnames = $surnames;
        $this->username = $username;
        $this->email = $email;
        $this->pass = $pass;
        $this->role = $role;
        $this->registerDate = $registerDate;
        $this->verified = $verified;
    }

    function getId() {
        return $this->id;
    }
    
    function getName() {
        return $this->name;
    }
    
    function getSurnames() {
        return $this->surnames;
    }
    
    function getUsername() {
        return $this->username;
    }
    
    function getEmail() {
        return $this->email;
    }
    
    function getPass() {
        return $this->pass;
    }
    
    function getRole() {
        return $this->role;
    }
    
    function getRegisterDate() {
        return $this->registerDate;
    }
    
    function getVerified() {
        return $this->verified;
    }
    
    function isVerified() {
        return $this->getVerified();
    }

    function setId($id) {
        $this->id = $id;
    }
    
    function setName($name) {
        $this->name = $name;
    }
    
    function setSurnames($surnames) {
        $this->surnames = $surnames;
    }
    
    function setUsername($username) {
        $this->username = $username;
    }

    function setEmail($email) {
        $this->email = $email;
    }
    
    function setPass($pass) {
        $this->pass = $pass;
    }
    
    function setRole($role) {
        $this->role = $role;        
    }
    
    function setRegisterDate($registerDate) {
        $this->registerDate = $registerDate;
    }
    
    function setVerified($verified) {
        $this->verified = $verified;
    }
    
    /*----------------------< Same for all classes >----------------------*/

    function getAttributes(){
        $attributes = [];
        foreach($this as $attribute => $value){
            $attributes[] = $attribute;
        }
        return $attributes;
    }

    function getValues(){
        $values = [];
        foreach($this as $value){
            $values[] = $value;
        }
        return $values;
    }
    
    function getAttributesValues(){
        $fullValues = [];
        foreach($this as $attribute => $value){
            $fullValues[$attribute] = $value;
        }
        return $fullValues;
    }
    
    function json() {
        return json_encode($this->getAttributesValues());
    }
    
    function read(){
        foreach($this as $attribute => $value){
            $this->$attribute = Request::read($attribute);
        }
    }
    
    function set(array $array, $pos = 0){
        foreach ($this as $field => $value) {
            if (isset($array[$pos]) ) {
                $this->$field = $array[$pos];
            }
            $pos++;
        }
    }
    
    function setFromAssociative(array $array){
        foreach($this as $index => $value){
            if(isset($array[$index])){
                $this->$index = $array[$index];
            }
        }
    }
    
    public function __toString() {
        $string = get_class() . ': ';
        foreach($this as $attribute=> $value){
            $string .= $attribute . ': ' . $value . ', ';
        }
        return substr($string, 0, -2);
    }
    
}