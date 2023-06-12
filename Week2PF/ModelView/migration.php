<?php

    $host = "localhost:";
    $port = 5432;
    $dbname = "ecommerce";
    $user = "postgres";
    $password = 'qazQAZ123';

    $connection_string = "host={$host} port={$port} dbname={$dbname} user={$user} password={$password} ";
    // $connection_string = "host=".$host." port=" .$port . " dbname=" .$dbname . " user=" . $user . " password=" . $password;

    $dbconn = pg_connect($connection_string);

    if ($dbconn) {

        echo "Connected to " . $dbname;
    } else {
        echo "Error connecting to the database";
    }




    echo "Hello World";

?>