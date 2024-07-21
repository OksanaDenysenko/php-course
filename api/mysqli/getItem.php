<?php

// database connection
global $conn;
require_once("db_conect.php");

// Checking the existence of the database
$dbname = "level2";
$sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "Database $dbname exists." . PHP_EOL;
} else {
    echo "Database $dbname does not exist." . PHP_EOL;
}

//Execution of the request
$sql = "SELECT * FROM items";
$result = $conn->query($sql);

//Checking the availability of results
if ($result->num_rows > 0) {
    //Creating an array of data
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Conversion of data array to JSON
    $json = json_encode(['items' => $data]);
    echo $json;
} else {
    echo "No results found";
}

// Close the database connection
$conn->close();
