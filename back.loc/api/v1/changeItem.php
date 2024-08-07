<?php

//CORS
header("Access-Control-Allow-Origin: http://front.loc");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

// database connection
global $conn;
require_once("db_conect.php");

//receives json  { id: 22, text: "...", checked: true }
$jsonGet = file_get_contents('php://input');
$json = json_decode($jsonGet, true);

$id = $json["id"]; // The ID of the record to update
$text = htmlspecialchars($json["text"]);// New values to update
$checked = $json["checked"]; //До $id і $checked не застосовую htmlspecialchars, бо ці поля, як я розумію, підтягуються автоматично

// SQL-request to update a record
$stmt = $conn->prepare("UPDATE items SET text = ?, checked = '$checked' WHERE id = $id"); // prepared request
$stmt->bind_param("s", $text);

if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(['ok' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}

$stmt->close();
$conn->close();
