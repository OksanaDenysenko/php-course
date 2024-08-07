<?php

//CORS
header("Access-Control-Allow-Origin: http://front.loc");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { // pre-line request processing
    http_response_code(200);
    exit;
}

// database connection
global $conn;
$dbname = 'level2';
require_once("db_conect.php");

//receives json { "login": "...", "pass":"..." }
$json = file_get_contents('php://input');
$data = json_decode($json, true);

//XSS attacks
$login = htmlspecialchars($data["login"]); // replacement of special characters with HTML entities
$password = htmlspecialchars($data["pass"]);

// If fields are empty
if (empty($login) || empty($password)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$hash_password =  password_hash($password, PASSWORD_DEFAULT); // Hashed password

// Додавання користувача до бази даних
$stmt = $conn->prepare("INSERT INTO users (login, pass) VALUES (?, ?)"); // prepared request
$stmt->bind_param("ss", $login, $hash_password);

if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(['ok' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}

$stmt->close();
$conn->close();