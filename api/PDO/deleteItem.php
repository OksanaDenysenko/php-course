<?php

// database connection
global $db;
require_once("db_conect.php");

//receives json  { id: 22 }
$jsonGet = file_get_contents('php://input');
$data = json_decode($jsonGet, true);
$id = $data['id'];

// Deleting a record
try {
    $stmt = $db->prepare("DELETE FROM items WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
} catch (PDOException $e) {
    echo "Error deleting record: " . $e->getMessage() . PHP_EOL;
    die();
}

// Updating ID numbers
try {
    $stmt = $db->prepare("UPDATE items SET id = id - 1 WHERE id > :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
} catch (PDOException $e) {
    echo "Error updating ID numbers: " . $e->getMessage() . PHP_EOL;
    die();
}

// Response in JSON
$response = ['ok' => true];
echo json_encode($response);

// Closing the connection
$db = null;
