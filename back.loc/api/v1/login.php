<?php

//CORS
header("Access-Control-Allow-Origin: http://front.loc");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { // обробка префлайн запиту
    http_response_code(200);
    exit;
}

// database connection
global $conn;
$dbname = "level2";
require_once("db_conect.php");

session_start(); //відкриття сесії

//receives json { "login": "...", "pass":"..." }
$json = file_get_contents('php://input');
$data = json_decode($json, true);
$login = $data["login"];
$password = $data["pass"];

// Якщо поля пусті
if (!isset($data['login']) || !isset($data['pass'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

// Запит до бази даних для перевірки користувача
$sql = "SELECT * FROM users WHERE login = '$login'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) { //кількість рядків з потрібним логіном
    $row = mysqli_fetch_assoc($result); // переводимо рядок в асоціативний масив

    if (password_verify($password, $row['pass'])) { //якщо хеш пароля співпадає
        $_SESSION['user_id'] = $row['id']; // збереження сесії
        $_SESSION['username'] = $row['login'];

        http_response_code(200);
        echo json_encode(['ok' => true]);
    }
    else {
        http_response_code(400);
        echo json_encode(['error' => 'Incorrect password']);
    }
}
else {
    http_response_code(400);
    echo json_encode(['error' => 'User not found']);
}

mysqli_close($conn); // закриття зєднання з БД