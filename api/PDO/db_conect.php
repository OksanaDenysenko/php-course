<?php

$dsn = "mysql:host=localhost;dbname=level2";
$username = "root";
$dbpass = "";

try {
    $dbh = new PDO($dsn, $username, $dbpass);
    echo "The connection is made";
} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage();
}
