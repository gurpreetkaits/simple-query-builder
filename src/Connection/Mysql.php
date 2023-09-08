<?php

namespace robinksp\querybuilder\Connection;

use PDO;

class Mysql
{
    protected $pdo;

    public function __construct()
    {
        $host = 'localhost';
        $username = "root";
        $password = '';
        $dbname = 'crud';

        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $this->pdo = new PDO($dsn, $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function executeQuery($query): array
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
