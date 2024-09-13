<?php

//CORS
header("Access-Control-Allow-Origin: http://front.loc");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: DELETE");
header('Access-Control-Allow-Credentials: true');

// database connection
global $conn;
require_once("db_conect.php");

//receives json  { id: 22 }
$jsonGet = file_get_contents('php://input');
$text = json_decode($jsonGet, true);

$id = htmlspecialchars(strip_tags($text["id"]));

// SQL-request to delete a record
$stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
$stmt->bind_param("i", $id);

//Execution of the request
if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(['ok' => true]);

} else {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}

$stmt->close();
$conn->close();
