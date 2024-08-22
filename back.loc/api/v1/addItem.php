<?php

//CORS
header("Access-Control-Allow-Origin: http://front.loc");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header('Access-Control-Allow-Credentials: true');

// database connection
global $conn;
require_once("db_conect.php");

//receives json { text: "..." }
$jsonGet = file_get_contents('php://input');
$text = json_decode($jsonGet, true);
$value_text=strip_tags(htmlspecialchars($text['text']));

//add item
$stmt = $conn->prepare("INSERT INTO items (text, checked) VALUES (?, 0)"); // prepared request
$stmt->bind_param("s", $value_text);

if ($stmt->execute()) {
    http_response_code(200);

    echo json_encode(['id' => $stmt->insert_id]);

} else {
    http_response_code(500);

    echo json_encode(['error' => 'Server error']);
}

$stmt->close();
$conn->close();
