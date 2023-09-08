
# simpleQueryBuilder

simpleQueryBuilder is a PHP library that provides a simple and easy-to-use query builder for constructing SQL queries. It allows you to build complex SELECT queries with various conditions, joins, and more. Currently building the library, it's not available for all type of databases. Contributions are welcome ❤️

# Features

<ul>
    <li>Build SELECT queries with custom columns.</li>
    <li>Add multiple conditions to the WHERE clause.</li>
    <li>Perform table joins with ease.</li>
    <li>Support for more query types can be easily added.</li>
</ul>

# Requirements
<ul>
    <li>PHP version 8.0 or higher.</li>
</ul>

# Installation

You can install simpleQueryBuilder via Composer. Simply add the following line to your <code>composer.json</code> file and run <code>composer install </code>:
</br>
```bash
{
    "require": {
        "robinksp/querybuilder": "^1.0"
    }
}

```

Alternatively, you can run the following command directly in your project root:

```bash

composer require robinksp/querybuilder

```

# Insert Example

```bash

require 'vendor/autoload.php';

use robinksp\querybuilder\Query;

if(isset($_POST['submit'])){
    unset($_POST['submit']);
    try {
        $qb = new Query();
        $qb->table('users')->insert($_POST);
        echo 'Inserted';
    } catch (\Throwable $th) {
            throw $th;
    }
}
```

# Contribution
Contributions are welcome! If you encounter any bugs, have feature requests, or want to improve the library, feel free to open an issue or submit a pull request.

# License
simpleQueryBuilder is open-source software licensed under the MIT License.

<hr>
Thank you for using simpleQueryBuilder! We hope this library simplifies your SQL query-building process. If you have any questions or need further assistance, please don't hesitate to reach out. Happy coding!



