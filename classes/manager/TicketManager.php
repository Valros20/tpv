<?php

class TicketManager {
    
    private $db;
    
    function __construct(DataBase $db) {
        $this->db = $db;
    }
    
    public function addTicket(Ticket $ticket) {
        $sql = 'insert into ticket(date, idmember, idclient) values (:date, :idmember, :idclient)';
        $params = array(
            'date' => $ticket->getDate(),
            'idmember' => $ticket->getIdmember(),
            'idclient' => $ticket->getIdclient()
        );
        $res = $this->db->execute($sql, $params);
        if($res) {
            $id = $this->db->getId();
            $ticket->setId($id);
        } else {
            $id = 0;
        }
        return $id;
    }
    
    public function editTicket(Ticket $ticket) {
        $sql = 'update ticket set date = :date, idmember = :idmember, idclient = :idclient where id = :id';
        $params = array(
            'date' => $ticket->getDate(),
            'idmember' => $ticket->getIdmember(),
            'idclient' => $ticket->getIdclient(),
            'id' => $ticket->getId()
        );
        $res = $this->db->execute($sql, $params);
        if($res) {
            $affectedRows = $this->db->getRowNumber();
        } else {
            $affectedRows = -1;
        }
        return $affectedRows;
    }
    
    public function getTicket($id){
        $sql = 'select * from ticket where id = :id';
        $params = array(
            'id' => $id,
        );
        $res = $this->db->execute($sql, $params);
        $statement = $this->db->getStatement();
        $ticket = new Ticket();
        if($res && $row = $statement->fetch()) {
            $ticket->set($row);
        } else {
            $ticket = null;
        }
        return $ticket;
    }
    
    public function getAllTicket() {
        $sql = 'select * from ticket';
        $res = $this->db->execute($sql);
        $tickets = array();
        if($res){
            $statement = $this->db->getStatement();
            while($row = $statement->fetch()) {
                $ticket = new Ticket();
                $ticket->set($row);
                $tickets[] = $ticket;
            }
        }
        return $tickets;
    }
    
    public function removeTicket($id) {
        $sql = 'delete from ticket where id = :id';
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