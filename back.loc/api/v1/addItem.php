<?php

//CORS
header("Access-Control-Allow-Origin: http://front.loc");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header('Access-Control-Allow-Credentials: true');

// database connection
global $conn;
require_once("db_conect.php");

//receives json { text: "..." }
$jsonGet = file_get_contents('php://input');
$text = json_decode($jsonGet, true);

//add item
$sql = "INSERT INTO items (text, checked) VALUES ('$text[text]', FALSE)";
if ($conn->query($sql) === TRUE) {
    $id = $conn->insert_id;
    echo json_encode(['id' => $id]);
} else {
    echo "Error adding item: " . $conn->error . PHP_EOL;
}

$conn->close();