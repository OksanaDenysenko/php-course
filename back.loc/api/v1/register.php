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
require_once("db_conect.php");

//receives json { "login": "...", "pass":"..." }
$json = file_get_contents('php://input');
$data = json_decode($json, true);

$login = $data["login"];
$password = $data["pass"] . "ZxCvBn"; // add additional characters for security

// If fields are empty
if (empty($login) || empty($password)) {
    http_response_code(400);

    echo json_encode(['error' => 'Invalid input']);

    exit;
}

//Login and password validation
if (!preg_match('/^[a-zA-Z0-9_]+$/', $login) || !preg_match('/^[a-zA-Z0-9_]+$/', $password)) {
    http_response_code(409);

    echo json_encode(['error' => 'Login and password can only contain Latin letters, numbers and an underscore']);

    exit;
}

// Preparation and execution of the request
$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE login = ?");
$stmt->bind_param("s", $login);

if (!$stmt->execute()) {
    http_response_code(500);

    echo json_encode(['error' => 'Server error']);

    exit;
}

$result = $stmt->get_result();
$count = $result->fetch_column();

if ($count > 0) {
    http_response_code(409);

    echo json_encode(['error' => 'A user with this login already exists']);

    exit;
}
$hash_password = password_hash($password, PASSWORD_DEFAULT); // Hashed password

//Add a user to the database
$stmt = $conn->prepare("INSERT INTO users (login, pass) VALUES (?, ?)"); // prepared request
$stmt->bind_param("ss", $login, $hash_password);

if ($stmt->execute()) {
    http_response_code(200);

    echo json_encode(['ok' => true]);

} else {
    http_response_code(500);

    echo json_encode(['error' => 'Server error']);
}

$stmt->close();
$conn->close();