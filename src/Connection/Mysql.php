<?php

namespace robinksp\querybuilder\Connection;

use PDO;

class Mysql
{
    private $host;
    private $database;
    private $username;
    private $password;
    private $charset;
    private $pdo;

    public function __construct($host, $database, $username, $password, $charset = 'utf8')
    {
        $this->host = $host;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
        $this->charset = $charset;
    }

    public function connect() : PDO
    {
        $dsn = "mysql:host={$this->host};dbname={$this->database};charset={$this->charset}";

        try {
            $this->pdo = new \PDO($dsn, $this->username, $this->password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        } catch (\PDOException $e) {
            // Handle connection errors
            die("Connection failed: " . $e->getMessage());
        }
    }
}
