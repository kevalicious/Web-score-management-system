<?php 

class Server
{
    private $hostname = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "livescoreoop";

    public function dbConnect()
    {

        try {
        
           $dsn = "mysql:host=".$this->getHost().";dbname=".$this->getDbname(); 
           $pdo = new PDO($dsn, $this->getUsername(), $this->getPassword());
           return $pdo;

        } catch (\PDOException $e) {
          echo $e->getMessage(); 
        }

    }

    public function getHost()
    {
        return $this->hostname;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getDbname()
    {
        return $this->dbname;
    }


}






?>