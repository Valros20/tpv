<?php

class UserManager {

    private $db;

    function __construct(DataBase $db) {
        $this->db = $db;
    }
    
    public function addUserAdmin(User $user) {
        $sql = 'insert into user(name, surnames, username, email, pass, role, registerDate, verified) values (:name, :surnames, :username, :email, :pass, :role, curdate(), 1)';
        $params = array(
            'name' => $user->getName(),
            'surnames' => $user->getSurnames(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'pass' => Util::encrypt($user->getPass()),
            'role' => $user->getRole(),
        );
        $res = $this->db->execute($sql, $params);
        if($res) {
            $id = $this->db->getId();
            $user->setId($id);
        } else {
            $id = 0;
        }
        return $id;
    }
    
    public function addUser(User $user) {
        $sql = 'insert into user(name, surnames, username, email, pass, role, registerDate, verified) values (:name, :surnames, :username, :email, :pass, :role, curdate(), 0)';
        $params = array(
            'name' => $user->getName(),
            'surnames' => $user->getSurnames(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'pass' => Util::encrypt($user->getPass()),
            'role' => 'normal',
        );
        $res = $this->db->execute($sql, $params);
        if($res) {
            $id = $this->db->getId();
            $user->setId($id);
        } else {
            $id = 0;
        }
        return $id;
    }
    
    function countAdmins() {
        $sql = 'select count(*) from user
                where role = "administrator"';
        $res = $this->db->execute($sql);
        $users = array();
        if($res){
            $statement = $this->db->getStatement();
            while($row = $statement->fetch()) {
                $user = new User();
                $user->set($row);
                $users[] = $user;
            }
        }
        return count($users);
    }
    
    public function editPass(User $user) {
        $sql = 'update user set pass = :pass where id = :id';
        $params = array(
            'pass' => Util::encrypt($user->getPass()),
            'id' => $user->getId()
        );
        $res = $this->db->execute($sql, $params);
        if($res) {
            $affectedRows = $this->db->getRowNumber();
        } else {
            $affectedRows = -1;
        }
        return $affectedRows;
    }
    
    public function editUser(User $user) {
        $sql = 'update user set name = :name, surnames = :surnames, username = :username, email = :email where id = :id';
        $params = array(
            'name' => $user->getName(),
            'surnames' => $user->getSurnames(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'verified' => '0',
            'id' => $user->getId(),
        );
        $res = $this->db->execute($sql, $params);
        if($res) {
            $affectedRows = $this->db->getRowNumber();
        } else {
            $affectedRows = -1;
        }
        return $affectedRows;
    }
    
    public function editUserAdmin(User $user) {
        $sql = 'update user set name = :name, surnames = :surnames, username = :username, email = :email, pass = :pass, role = :role, verified = :verified where id = :id';
        $params = array(
            'name' => $user->getName(),
            'surnames' => $user->getSurnames(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'pass' => Util::encrypt($user->getPass()),
            'role' => $user->getRole(),
            'verified' => $user->getVerified(),
            'id' => $user->getId(),
        );
        $res = $this->db->execute($sql, $params);
        if($res) {
            $affectedRows = $this->db->getRowNumber();
        } else {
            $affectedRows = -1;
        }
        return $affectedRows;
    }
    
    public function editUserPlus(User $user) {
        $sql = 'update user set name = :name, surnames = :surnames, username = :username, email = :email where id = :id';
        $params = array(
            'name' => $user->getName(),
            'surnames' => $user->getSurnames(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'id' => $user->getId(),
        );
        $res = $this->db->execute($sql, $params);
        if($res) {
            $affectedRows = $this->db->getRowNumber();
        } else {
            $affectedRows = -1;
        }
        return $affectedRows;
    }
    
    public function removeUser($id) {
        $sql = 'delete from user where id = :id';
        $params = array(
            'id' => $id,
        );
        $res = $this->db->execute($sql, $params);
        if($res) {
            $affectedRows = $this->db->getRowNumber();
        } else {
            $affectedRows = -1;
        }
        return $affectedRows;
    }
    
    public function setVerified(User $user) {
        /*$sqlold = 'update user set email = :email , verified = :verified where id = :id';*/
        $sql = 'update user set verified = :verified where id = :id';
        /*$paramsold = array(
            'email' => $user->getEmail(),
            'verified' => $user->getVerified(),
            'id' => $user->getId()
        );*/
        $params = array(
            'verified' => $user->getVerified(),
            'id' => $user->getId(),
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
        $sql = 'select * from user';
        $res = $this->db->execute($sql);
        $users = array();
        if($res){
            $statement = $this->db->getStatement();
            while($row = $statement->fetch()) {
                $user = new User();
                $user->set($row);
                $users[] = $user;
            }
        }
        return $users;
    }
    
    public function getUserFromEmail($email) {
        $sql = 'select * from user where email = :email';
        $params = array(
            'email' => $email
        );
        $res = $this->db->execute($sql, $params);
        $statement = $this->db->getStatement();
        $user = new User();
        if($res && $row = $statement->fetch()) {
            $user->set($row);
        } else {
            $user = null;
        }
        return $user;
    }
    
    public function getUserFromUsername($username) {
        $sql = 'select * from user where username = :username';
        $params = array(
            'username' => $username
        );
        $res = $this->db->execute($sql, $params);
        $statement = $this->db->getStatement();
        $user = new User();
        if($res && $row = $statement->fetch()) {
            $user->set($row);
        } else {
            $user = null;
        }
        return $user;
    }

    public function getUser($id) {
        $sql = 'select * from user where id = :id';
        $params = array(
            'id' => $id,
        );
        $res = $this->db->execute($sql, $params);
        $statement = $this->db->getStatement();
        $user = new User();
        if($res && $row = $statement->fetch()) {
            $user->set($row);
        } else {
            $res = null;
        }
        return $user;
    }
    
}