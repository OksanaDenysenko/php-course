<?php

//CORS
header("Access-Control-Allow-Origin: http://front.loc");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { // обробка префлайн запиту
    http_response_code(200);
    exit;
}

// database connection
global $conn;
$dbname = "level2";
require_once("db_conect.php");

//receives json { "login": "...", "pass":"..." }
$json = file_get_contents('php://input');
$data = json_decode($json, true);
$login = $data['login'];
$password = $data['pass'];

// Якщо поля пусті
if (!isset($data['login']) || !isset($data['pass'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$hash_password =  password_hash($data['pass'], PASSWORD_DEFAULT); // Хешований пароль

// Перевірка наявності користувача з таким логіном
$sql = "SELECT * FROM users WHERE login = '$login'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo json_encode(['error' => 'User already exists']);
    exit;
}

// Додавання користувача до бази даних
$sql = "INSERT INTO users (login, pass) VALUES ('$login', '$hash_password')";
if (mysqli_query($conn, $sql)) {
    http_response_code(200);
    echo json_encode(['ok' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}

mysqli_close($conn);