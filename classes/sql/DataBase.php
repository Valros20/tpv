<?php

class DataBase {
    
    private $connection, $statement;

    function __construct($class = 'Constants') {
        try{
            $this->connection = new PDO(
                'mysql:host=' . $class::SERVER . ';dbname=' . $class::DATABASE,
                $class::USER,
                $class::PASSWORD,
                array(
                    PDO::ATTR_PERSISTENT => true,
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8'
                )
            );
        } catch (PDOException $e){
            $this->connection = null;
        }
    }

    function execute($sql, array $param = array()) {
        $this->statement = $this->connection->prepare($sql);
        foreach ($param as $paramName => $paramValue) {
            $this->statement->bindValue($paramName, $paramValue);
        }
        $r = $this->statement->execute();//true o false
        /*
        echo $sql . '<br>';
        echo Util::varDump($param);
        echo Util::varDump($this->statement->errorInfo());
        //*/
        return $r;
    }
    
    function isConnected() {
        return $this->connection !== null;
    }
    
    function closeConnection() {
        $this->connection = null;
    }
    
    function getRowNumber() {
        return $this->statement->rowCount();
    }
    
    function getId() {
        return $this->connection->lastInsertId();
    }
    
    function getStatement(){
        return $this->statement;
    }
}