<?php

//CORS
header("Access-Control-Allow-Origin: http://front.loc");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { // pre-line request processing
    http_response_code(200);
    exit;
}

// database connection
global $conn;
$dbname = "level2";
require_once("db_conect.php");

session_start(); //session opening

//receives json { "login": "...", "pass":"..." }
$json = file_get_contents('php://input');
$data = json_decode($json, true);
$login = $data["login"];
$password = $data["pass"];

// If fields are empty
if (!isset($data['login']) || !isset($data['pass'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

// Query the database to verify the user
$sql = "SELECT * FROM users WHERE login = '$login'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) { //the number of lines with the required login
    $row = mysqli_fetch_assoc($result); //we convert the string into an associative array

    if (password_verify($password, $row['pass'])) { //if the password hash matches
        $_SESSION['user_id'] = $row['id']; //saving the session
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

mysqli_close($conn); //
// closing the connection to the database