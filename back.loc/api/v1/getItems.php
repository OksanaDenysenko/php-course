<?php

//CORS
header("Access-Control-Allow-Origin: http://front.loc");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Access-Control-Allow-Credentials: true');

// database connection
global $conn;
require_once("db_conect.php");
$table="items";

//Execution of the request
$sql = "SELECT * FROM $table";
$result = $conn->query($sql);

//Creating an array of data
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Conversion of data array to JSON
$json = json_encode(['items' => $data]);
echo $json;

// Close the database connection
$conn->close();
