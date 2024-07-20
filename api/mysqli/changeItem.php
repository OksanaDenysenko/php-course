<?php

// database connection
global $conn;
$dbname = "level2";
require_once("db_conect.php");

//receives json  { id: 22, text: "...", checked: true }
$jsonGet = file_get_contents('php://input');
$json = json_decode($jsonGet, true);

$id = $json["id"]; // The ID of the record to update
$text = $json["text"];// New values to update
$checked = $json["checked"];

//$id = 3; // Для перевірки
//$text = "cook";
//$checked = TRUE;

// SQL-request to update a record
$sql = "UPDATE items SET text = '$text', checked = '$checked' WHERE id = $id";

// Execution of the request
if ($conn->query($sql) === TRUE) {
    echo json_encode(['ok' => true]);
} else {
    echo "Error updating record: " . $conn->error . "\n";
}

$conn->close();
