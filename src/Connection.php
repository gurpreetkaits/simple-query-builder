<?php

namespace robinksp\querybuilder;

use Exception;
use PDO;
use robinksp\querybuilder\Connection\Mysql;

class Connection
{
    public function __construct(
        array $config
    ) {
        if(!in_array('database',$config) && !in_array('hostname',$config) && !in_array('username',$config)){
            return new Exception('all keys required database,hostname,username cannot be empty');
        }
        // $connection = $config['connection'] ?: 'mysql';
        $database = $config['database'];
        $host = $config['hostname'];
        $user = $config['username'];
        $password = $config['password'] ?: '';
        return (new Mysql($host, $database, $user, $password))->connect();
    }
}
