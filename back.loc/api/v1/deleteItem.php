<?php

//CORS
header("Access-Control-Allow-Origin: http://front.loc");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: DELETE, OPTIONS");
header('Access-Control-Allow-Credentials: true');

// database connection
global $conn;
require_once("db_conect.php");

//receives json  { id: 22 }
$jsonGet = file_get_contents('php://input');
$text = json_decode($jsonGet, true);

$id = $text["id"];

// SQL-request to delete a record
$sql = "DELETE FROM items WHERE id = $id";

//Execution of the request
if ($conn->query($sql) === TRUE) {
    echo json_encode(['ok' => true]);
} else {
    echo "Error deleting record: " . $conn->error . PHP_EOL;
}

$conn->close();
