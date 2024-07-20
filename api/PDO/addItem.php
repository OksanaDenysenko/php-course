<?php

// database connection
global $db;
require_once("db_conect.php");

//receives json { text: "..." }
$jsonGet = file_get_contents('php://input');
$text = json_decode($jsonGet, true);
//$text="mmmm"; // для перевірки

//add item
$sql = "INSERT INTO items (text, checked) VALUES (:text, FALSE)";
$stmt = $db->prepare($sql);
$stmt->bindParam(':text', $text);

if ($stmt->execute()) {
    $id = $db->lastInsertId();
    echo json_encode(['id' => $id]);
} else {
    echo "Error adding item: " . $stmt->errorInfo()[2] . PHP_EOL;
}

// Closing the connection to the database
$db = null;
