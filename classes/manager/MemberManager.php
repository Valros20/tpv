<?php

class MemberManager {
    
    private $db;
    
    function __construct(DataBase $db) {
        $this->db = $db;
    }
    
    public function addMember(Member $member) {
        $sql = 'insert into member(login, password) values (:login, :password)';
        $params = array(
            'login' => $member->getLogin(),
            'password' => $member->getPassword()
        );
        $res = $this->db->execute($sql, $params);
        if($res) {
            $id = $this->db->getId();
            $member->setId($id);
        } else {
            $id = 0;
        }
        return $id;
    }
    
    public function editMember(Member $member) {
        $sql = 'update member set login = :login, password = :password where id = :id';
        $params = array(
            'login' => $member->getLogin(),
            'password' => $member->getPassword(),
            'id' => $member->getId()
        );
        $res = $this->db->execute($sql, $params);
        if($res) {
            $affectedRows = $this->db->getRowNumber();
        } else {
            $affectedRows = -1;
        }
        return $affectedRows;
    }
    
    public function getMember($id) {
        $sql = 'select * from member where id = :id';
        $params = array(
            'id' => $id,
        );
        $res = $this->db->execute($sql, $params);
        $statement = $this->db->getStatement();
        $member = new Member();
        if($res && $row = $statement->fetch()) {
            $member->set($row);
        } else {
            $res = null;
        }
        return $member;
    }
    
    public function getMemberFromLogin($login) {
        $sql = 'select * from member where login = :login';
        $params = array(
            'login' => $login
        );
        $res = $this->db->execute($sql, $params);
        $statement = $this->db->getStatement();
        $member = new Member();
        if($res && $row = $statement->fetch()) {
            $member->set($row);
        } else {
            $member = null;
        }
        return $member;
    }
    
    public function getAllMember() {
        $sql = 'select * from member';
        $res = $this->db->execute($sql);
        $members = array();
        if($res){
            $statement = $this->db->getStatement();
            while($row = $statement->fetch()) {
                $member = new Member();
                $member->set($row);
                $members[] = $member;
            }
        }
        return $members;
    }
    
    public function removeMember($id){
        $sql = 'delete from member where id = :id';
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
    
    function countMember(){
        $sql = 'select count(*) from member';
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
    
    function getPagedMember($offset, $rpp){
        $sql = 'select * from member limit '. $offset . ', ' . $rpp;
        $res = $this->db->execute($sql);
        $members = array();
        if($res){
            $statement = $this->db->getStatement();
            while($row = $statement->fetch()) {
                $member = new Member();
                $member->set($row);
                $members[] = $member;
            }
        }
        return $members;
    }
    
}