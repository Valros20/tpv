<?php

class TicketDetailManager {
    
    private $db;
    
    function __construct(DataBase $db) {
        $this->db = $db;
    }
    
    public function addTicketDetail(Ticketdetail $ticketDetail) {
        $sql = 'insert into ticketdetail(idticket, idproduct, quantity, price) '.
                    'values (:idticket, :idproduct, :quantity, :price)';
        $params = array(
            'idticket' => $ticketDetail->getIdticket(),
            'idproduct' => $ticketDetail->getIdproduct(),
            'quantity' => $ticketDetail->getQuantity(),
            'price' => $ticketDetail->getPrice()
        );
        $res = $this->db->execute($sql, $params);
        if($res) {
            $id = $this->db->getId();
            $ticketDetail->setId($id);
        } else {
            $id = 0;
        }
        return $id;
    }
    
    public function editTicketDetail(Ticketdetail $ticketDetail) {
        $sql = 'update ticketdetail set idticket = :idticket, idproduct = :idproduct, '.
                    'quantity = :quantity, price = :price where id = :id';
        $params = array(
            'idticket' => $ticketDetail->getIdticket(),
            'idproduct' => $ticketDetail->getIdproduct(),
            'quantity' => $ticketDetail->getQuantity(),
            'price' => $ticketDetail->getPrice(),
            'id' => $ticketDetail->getId()
        );
        $res = $this->db->execute($sql, $params);
        if($res) {
            $affectedRows = $this->db->getRowNumber();
        } else {
            $affectedRows = -1;
        }
        return $affectedRows;
    }
    
    public function getTicketDetail($id){
        $sql = 'select * from ticketdetail where id = :id';
        $params = array(
            'id' => $id,
        );
        $res = $this->db->execute($sql, $params);
        $statement = $this->db->getStatement();
        $ticketDetail = new Ticketdetail();
        if($res && $row = $statement->fetch()) {
            $ticketDetail->set($row);
        } else {
            $ticketDetail = null;
        }
        return $ticketDetail;
    }
    
    public function getAllTicketDetail() {
        $sql = 'select * from ticketdetail';
        $res = $this->db->execute($sql);
        $ticketdetails = array();
        if($res){
            $statement = $this->db->getStatement();
            while($row = $statement->fetch()) {
                $ticketdetail = new Ticketdetail();
                $ticketdetail->set($row);
                $ticketdetails[] = $ticketdetail;
            }
        }
        return $ticketdetails;
    }
    
    public function getAllTicketDetailFromTicket($idticket) {
        $sql = 'select * from ticketdetail where idticket = :idticket';
        $params = array(
            'idticket' => $idticket
            );
        $res = $this->db->execute($sql, $params);
        $ticketdetails = array();
        if($res){
            $statement = $this->db->getStatement();
            while($row = $statement->fetch()) {
                $ticketdetail = new Ticketdetail();
                $ticketdetail->set($row);
                $ticketdetails[] = $ticketdetail;
            }
        }
        return $ticketdetails;
    }
    
    public function removeTicketDetail($id) {
        $sql = 'delete from ticketdetail where id = :id';
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
}