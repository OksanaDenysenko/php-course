<?php

// database connection
global $db;
require_once("db_conect.php");

//receives json  { id: 22, text: "...", checked: true }
$jsonGet = file_get_contents('php://input');
$data = json_decode($jsonGet, true);

$id = $data['id'];
$text = $data['text'];
$checked = $data['checked'];

// Update record
try {
    $stmt = $db->prepare("UPDATE items SET text = :text, checked = :checked WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':text', $text);
    $stmt->bindParam(':checked', $checked);
    $stmt->execute();
} catch (PDOException $e) {
    echo "Error updating record: " . $e->getMessage() . PHP_EOL;
    die();
}

// The response is in JSON format
$response = ['ok' => true];
echo json_encode($response);

// Closing the connection
$db = null;
