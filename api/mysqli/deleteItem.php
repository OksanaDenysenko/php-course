<?php
global $conn;
require_once("db_conect.php");

//приймає json  { id: 22 }
$jsonGet = file_get_contents('php://input');
$text = json_decode($jsonGet, true);

$id=$text["id"];
//$id=1;
// SQL-запит для видалення запису
$sql = "DELETE FROM items WHERE id = $id";

// Виконання запиту
if ($conn->query($sql) === TRUE) {
    echo json_encode(['ok' => true]);
} else {
    echo "Помилка видалення запису та оновлення інкремента: " . $conn->error . "\n";
}

// Оновлення номерів ID
$sql_update = "UPDATE items SET id = id - 1 WHERE id > $id";

if ($conn->query($sql_update) === TRUE) {
    echo "Номери ID успішно оновлено!\n";
} else {
    echo "Помилка оновлення номерів ID: " . $conn->error . "\n";
}

$conn->close();
