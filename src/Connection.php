<?php

namespace robinksp\querybuilder;

use PDO;
use robinksp\querybuilder\Connection\Mysql;

class Connection
{
    public function __construct(
        array $config
    ) {
        // $connection = $config['connection'] ?: 'mysql';
        $database = $config['database'] ?: 'test';
        $host = $config['hostname'] ?: 'localhost';
        $user = $config['username'] ?: '';
        $password = $config['password'] ?: '';
        return (new Mysql($host, $database, $user, $password))->connect();
    }
}
