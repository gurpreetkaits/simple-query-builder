<?php

namespace robinksp\querybuilder;

class CreateConnectionFile
{
    public static function createConnectionFile()
    {
        $connectionDetails = [];

        // Prompt user for connection details
        $connectionDetails['host'] = readline('Enter the database host: ');
        $connectionDetails['database'] = readline('Enter the database name: ');
        $connectionDetails['username'] = readline('Enter the database username: ');
        $connectionDetails['password'] = readline('Enter the database password: ');

        // Serialize and write connection details to .connection file
        file_put_contents($_SERVER['HOME'] . '/.connection', serialize($connectionDetails));

        echo "Connection details saved to .connection file.\n";
    }
}
