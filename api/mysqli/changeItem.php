<?php
// підключення до БД
global $conn;
$dbname="level2";
require_once("db_conect.php");

//приймає json  { id: 22, text: "...", checked: true }
$jsonGet = file_get_contents('php://input');
$json = json_decode($jsonGet, true);

$id = $json["id"]; // ID запису, який потрібно оновити
$text = $json["text"];// Нові значення для оновлення
$checked = $json["checked"];

//$id = 3; // ID запису, який потрібно оновити
//$text = "cook";// Нові значення для оновлення
//$checked = TRUE;

// SQL-запит для оновлення запису
$sql = "UPDATE items SET text = '$text', checked = '$checked' WHERE id = $id";

// Виконання запиту
if ($conn->query($sql) === TRUE) {
    echo json_encode(['ok' => true]);
} else {
    echo "Помилка оновлення запису: " . $conn->error . "\n";
}

$conn->close();
