<?php

class Family {
    
    use Comun;
    
    private $id, $family;
    
    function __construct($id = null, $family = null) {
        $this->id = $id;
        $this->family = $family;
    }
    
    /*getter & setter generator http://www.kjetil-hartveit.com/blog/1/setter-and-getter-generator-for-php-javascript-c%2B%2B-and-csharp*/
    
    function setId($id) { $this->id = $id; }
    function getId() { return $this->id; }
    function setFamily($family) { $this->family = $family; }
    function getFamily() { return $this->family; }
}