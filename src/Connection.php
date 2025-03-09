<?php

namespace GurpreetKaits\Builder;

use Exception;
use PDO;
use GurpreetKaits\Builder\Connection\Mysql;

class Connection
{
    private static $database;
    private static $host;
    private static $user;
    private static $password;

    public function __construct(
        array $config
    ) {
        if (!in_array('database', $config) && !in_array('hostname', $config) && !in_array('username', $config)) {
            return new Exception('all keys required database,hostname,username cannot be empty');
        }
        self::$database = $config['database'] ?: 'test';
        self::$host = $config['hostname'] ?: 'localhost';
        self::$user = $config['username'] ?: '';
        self::$password = $config['password'] ?: '';
    }

    public static function connect()
    {
        return (new Mysql(self::$host, self::$database, self::$user, self::$password))->connect();
    }
}
