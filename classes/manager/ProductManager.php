<?php

class ProductManager {
    
    private $db;
    
    function __construct(DataBase $db) {
        $this->db = $db;
    }
    
    public function addProduct(Product $product) {
        $sql = 'insert into product(idfamily, product, price, description) '.
                    'values (:idfamily, :product, :price, :description)';
        $params = array(
            'idfamily' => $product->getIdfamily(),
            'product' => $product->getProduct(),
            'price' => $product->getPrice(),
            'description' => $product->getDescription()            
        );
        $res = $this->db->execute($sql, $params);
        if($res) {
            $id = $this->db->getId();
            $product->setId($id);
        } else {
            $id = 0;
        }
        return $id;
    } 
    
    public function editProduct(Product $product) {
        $sql = 'update product set idfamily = :idfamily, product = :product, price = :price, '.
                    'description = :description where id = :id';
        $params = array(
            'idfamily' => $product->getIdfamily(),
            'product' => $product->getProduct(),
            'price' => $product->getPrice(),
            'description' => $product->getDescription(),
            'id' => $product->getId()
        );
        $res = $this->db->execute($sql, $params);
        if($res) {
            $affectedRows = $this->db->getRowNumber();
        } else {
            $affectedRows = -1;
        }
        return $affectedRows;
    }
    
    public function getProduct($id){
        $sql = 'select * from product where id = :id';
        $params = array(
            'id' => $id,
        );
        $res = $this->db->execute($sql, $params);
        $statement = $this->db->getStatement();
        $product = new Product();
        if($res && $row = $statement->fetch()) {
            $product->set($row);
        } else {
            $product = null;
        }
        return $product;
    }
    
    public function getAll() {
        $sql = 'select * from product';
        $res = $this->db->execute($sql);
        $products = array();
        if($res){
            $statement = $this->db->getStatement();
            while($row = $statement->fetch()) {
                $product = new Product();
                $product->set($row);
                $products[] = $product;
            }
        }
        return $products;
    }
    
    public function removeProduct($id) {
        $sql = 'delete from product where id = :id';
        $params = array(
            'id' => $id
        );
        $res = $this->db->execute($sql, $params);
        if($res) {
            $affectedRows = $this->db->getRowNumber();
        } else {
            $affectedRows = -1;
        }
        return $affectedRows;
    }
    
    function countProduct(){
        $sql = 'select count(*) from product';
        $params = array(

            );
        $res = $this->db->execute($sql, $params);
        $cuenta = 0;
        if($res){
            $sentencia = $this->db->getStatement();
            while($fila = $sentencia->fetch()){
                $cuenta = $fila[0];
            }
        }
        return $cuenta;
    }
    
    function getPagedProduct($offset, $rpp) {
        $sql = 'select * from product limit '. $offset . ', ' . $rpp;
        $res = $this->db->execute($sql);
        $products = array();
        if($res){
            $statement = $this->db->getStatement();
            while($row = $statement->fetch()) {
                $product = new Product();
                $product->set($row);
                $products[] = $product;
            }
        }
        return $products;
    }
    
    function getProductsFromFamily($idfamily){
        $sql = 'select * from product where idfamily = :idfamily';
        $params = array(
            'idfamily' => $idfamily
            );
        
        $res = $this->db->execute($sql, $params);
        $products = array();
        if($res){
            $statement = $this->db->getStatement();
            while($row = $statement->fetch()) {
                $product = new Product();
                $product->set($row);
                $products[] = $product;
            }
        }
        return $products;
    }
}