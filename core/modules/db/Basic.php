<?php

namespace Core\Db;

use Core\Main\Settings;
use PDO;
use PDOException;

class Basic
{
    private $dbName;
    private $dbUser;
    private $dbPassword;
    private $dbHost;
    private $conn;

    public function __construct(string $dbName = 'default') {
        $this->dbName = $dbName;
        $arSettings = Settings::getDbParams($dbName);
        $this->dbHost = $arSettings['host'];
        $this->dbUser = $arSettings['user'];
        $this->dbPassword = $arSettings['password'];

        $this->connect();
    }

    public function connect() : bool
    {
        try {
            $this->conn = new PDO(
                `mysql:host=$this->dbHost;dbname=$this->dbName`, 
                $this->dbUser, 
                $this->dbPassword
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        }   
        catch(PDOException $e) {
            echo 'Connection false: ' . $e->getMessage();
            $this->conn = false;
            return false;
        }     
    }

}