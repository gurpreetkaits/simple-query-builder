
# simpleQueryBuilder

Simple Wuery Builder is a PHP library that provides a simple and easy-to-use query builder for constructing SQL queries. It allows you to build SELECT queries with various conditions, joins, and more. Currently building the library, it's not available for all types of databases. Contributions are welcome ❤️

# Installation

```
 
composer require robinksp/simple-query-builder 

```
# Simple Example Of Execution

```
 <?php

ini_set('display_errors', 1);

use robinksp\querybuilder\Connection;
use robinksp\querybuilder\Query;

require 'vendor/autoload.php';


$config = [
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => 'password',
    'database' => 'test'
];
$connection = (new Connection($config))::connect();

$selectQuery = (new Query($connection))
    ->table('award_icons')
    ->select('*')
    ->where('id', '=', 33)
    ->get();

echo '<pre>';
print_r($selectQuery);

Array
(
    [0] => Array
        (
            [id] => 33
            [title] => Award
            [icon] => award
            [created_at] => 2023-10-09 14:59:17
            [updated_at] => 2023-10-09 14:59:17
        )

)


```
