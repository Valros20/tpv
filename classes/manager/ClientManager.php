<?php

class ClientManager {
    
    private $db;
    
    function __construct(DataBase $db) {
        $this->db = $db;
    }
    
    public function addClient(Client $client) {
        $sql = 'insert into client(name, surname, tin, address, location, postalcode, province, email)'.
                    'values (:name, :surname, :tin, :address, :location, :postalcode, :province, :email)';
        $params = array(
            'name' => $client->getName(),
            'surname' => $client->getSurname(),
            'tin' => $client->getTin(),
            'address' => $client->getAddress(),
            'location' => $client->getLocation(),
            'postalcode' => $client->getPostalcode(),
            'province' => $client->getProvince(),
            'email' => $client->getEmail()
        );
        $res = $this->db->execute($sql, $params);
        if($res) {
            $id = $this->db->getId();
            $client->setId($id);
        } else {
            $id = 0;
        }
        return $id;
    }
    public function editClient(Client $client) {
        $sql = 'update client set name = :name, surname = :surname, tin = :tin,'. 
                   'address = :address, location = :location, postalcode = :postalcode,'. 
                   'province = :province, email = :email where id = :id';
        $params = array(
        'name' => $client->getName(),
        'surname' => $client->getSurname(),
        'tin' => $client->getTin(),
        'address' => $client->getAddress(),
        'location' => $client->getLocation(),
        'postalcode' => $client->getPostalcode(),
        'province' => $client->getProvince(),
        'email' => $client->getEmail(),
        'id' => $client->getId()
        );
        $res = $this->db->execute($sql, $params);
        if($res) {
            $affectedRows = $this->db->getRowNumber();
        } else {
            $affectedRows = -1;
        }
        return $affectedRows;
    }
    public function getAll() {
        $sql = 'select * from client where';
        $res = $this->db->execute($sql);
        $clients = array();
        if($res){
            $statement = $this->db->getStatement();
            while($row = $statement->fetch()) {
                $client = new Client();
                $client->set($row);
                $clients[] = $client;
            }
        }
        return $clients;
    }
    public function getClient($id){
        $sql = 'select * from client where id = :id';
        $params = array(
            'id' => $id
        );
        $res = $this->db->execute($sql, $params);
        $statement = $this->db->getStatement();
        $client = new Client();
        if($res && $row = $statement->fetch()) {
            $client->set($row);
        } else {
            $client = null;
        }
        return $client;
    }
    function getAllClientFromName($name){
        $sql = 'select * from client where name like :name';
        $params = array(
            'name' => '%' . $name . '%'
            );
        $res = $this->db->execute($sql, $params);
        $clients = array();
        if($res){
            $statement = $this->db->getStatement();
            while($row = $statement->fetch()) {
                $client = new Client();
                $client->set($row);
                $clients[] = $client;
            }
        }
        return $clients;
    }
    function getPagedClient($offset, $rpp){
        $sql = 'select * from client limit '. $offset . ', ' . $rpp;
        $res = $this->db->execute($sql);
        $clients = array();
        if($res){
            $statement = $this->db->getStatement();
            while($row = $statement->fetch()) {
                $client = new Client();
                $client->set($row);
                $clients[] = $client;
            }
        }
        return $clients;
    }
    public function removeClient($id) {
        $sql = 'delete from client where id = :id';
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
    function countClient(){
        $sql = 'select count(*) from client';
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
}