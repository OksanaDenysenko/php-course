<?php

//CORS
header("Access-Control-Allow-Origin: http://front.loc");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { //pre-line request processing
    http_response_code(200);

    exit;
}

// database connection
global $conn;
require_once("db_conect.php");

session_start(); //session opening

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
}

//SQL injection
$stmt = $conn->prepare("SELECT * FROM users WHERE login = ?"); // prepared request
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();

if (mysqli_num_rows($result) != 1) { //if the user is not registered
    http_response_code(401);

    exit(json_encode(['error' => 'User not found']));
}

$row = mysqli_fetch_assoc($result); // convert the string into an associative array

if (!password_verify($password, $row['pass'])) { //if the password hash does not match
    http_response_code(400);

    exit(json_encode(['error' => 'Incorrect password']));
}

try {
    $token = bin2hex(random_bytes(32));
    setcookie('auth_token', $token, time() + 3600, '/', '', true, true);// store the token in a cookie for 1 hour
    $_SESSION['auth_token'] = $token; // store the token in the session
    http_response_code(200);

    echo json_encode(['ok' => true]);

} catch (\Random\RandomException $e) {
    error_log("Error generating random token: " . $e->getMessage());
    http_response_code(500);

    echo json_encode(['error' => 'Server error']);
}

$stmt->close();
$conn->close(); // closing the connection