<?php

$dsn = "mysql:host=localhost";
$username = "root";

try {
    $dbh = new PDO($dsn);
    echo "Database connection successfully established";

    $sql = "CREATE DATABASE level2";
    if ($dbh->query($sql)) {
        echo "Database my_database created successfully";
    } else {
        echo "Error creating database: ";
    }
} catch (PDOException $e) {
    echo "Database connection error: " . $e->getMessage();
}

// Close the connection
$dbh=null;
