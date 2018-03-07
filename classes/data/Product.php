<?php

class Product {

    use Comun;
    
    private $id, $idfamily, $product, $price, $description;
    
    function __construct($id = null, $idfamily = null, $product = null, $price = null, $description = null) {
        $this->id = $id;
        $this->idfamily = $idfamily;
        $this->product = $product;
        $this->price = $price;
        $this->description = $description;
    }
    
    /*getter & setter generator http://www.kjetil-hartveit.com/blog/1/setter-and-getter-generator-for-php-javascript-c%2B%2B-and-csharp*/

    function setId($id) { $this->id = $id; }
    function getId() { return $this->id; }
    function setIdfamily($idfamily) { $this->idfamily = $idfamily; }
    function getIdfamily() { return $this->idfamily; }
    function setProduct($product) { $this->product = $product; }
    function getProduct() { return $this->product; }
    function setPrice($price) { $this->price = $price; }
    function getPrice() { return $this->price; }
    function setDescription($description) { $this->description = $description; }
    function getDescription() { return $this->description; }
    
    /* común a todas las clases */

}