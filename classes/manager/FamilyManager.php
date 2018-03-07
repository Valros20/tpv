<?php

class FamilyManager {
    
    private $db;
    
    function __construct(DataBase $db) {
        $this->db = $db;
    }
    
    public function addFamily(Family $family) {
        $sql = 'insert into family(family) values (:family)';
        $params = array(
            'family' => $family->getFamily()
        );
        $res = $this->db->execute($sql, $params);
        if($res) {
            $id = $this->db->getId();
            $family->setId($id);
        } else {
            $id = 0;
        }
        return $id;
    }
    
    public function editFamily(Family $family) {
        $sql = 'update family set family = :family where id = :id';
        $params = array(
            'family' => $family->getFamily(),
            'id' => $family->getId()
        );
        $res = $this->db->execute($sql, $params);
        if($res) {
            $affectedRows = $this->db->getRowNumber();
        } else {
            $affectedRows = -1;
        }
        return $affectedRows;
    }
    
    public function getFamily($id){
        $sql = 'select * from family where id = :id';
        $params = array(
            'id' => $id,
        );
        $res = $this->db->execute($sql, $params);
        $statement = $this->db->getStatement();
        $family = new Family();
        if($res && $row = $statement->fetch()) {
            $family->set($row);
        } else {
            $family = null;
        }
        return $family;
    }
    public function getAll() {
        $sql = 'select * from family';
        $res = $this->db->execute($sql);
        $families = array();
        if($res){
            $statement = $this->db->getStatement();
            while($row = $statement->fetch()) {
                $family = new Family();
                $family->set($row);
                $families[] = $family;
            }
        }
        return $families;
    }
    
    public function removeFamily($id) {
        $sql = 'delete from family where id = :id';
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
    
    public function getFromName($name) {
        $sql = 'select * from family where family = :family';
        $params = array(
            'family' => $name,
        );
        $res = $this->db->execute($sql, $params);
        $statement = $this->db->getStatement();
        $family = new Family();
        if($res && $row = $statement->fetch()) {
            $family->set($row);
        } else {
            $family = null;
        }
        return $family;
    }
    
}