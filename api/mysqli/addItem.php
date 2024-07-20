<?php
// підключення до БД
global $conn;
$dbname="level2";
require_once("db_conect.php");

//приймає json { text: "..." }
$jsonGet = file_get_contents('php://input');
$text = json_decode($jsonGet, true);

// Чи існує таблиця items
$sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$dbname' AND TABLE_NAME = 'items'";
$result = $conn->query($sql);

if ($result->num_rows == 0) { // якщо не існує, створити таблицю
    $sql = "CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    text VARCHAR(255) NOT NULL,
    checked BOOL NOT NULL
)";
    if ($conn->query($sql) === TRUE) {
        echo "Таблиця items створена успішно!\n";
    } else {
        echo "Помилка створення таблиці: " . $conn->error . "\n";
    }
}

//додавання item
$sql = "INSERT INTO items (text, checked) VALUES ('$text', FALSE)";
if ($conn->query($sql) === TRUE) {
    $id= $conn->insert_id;
    echo json_encode(['id' => $id]);
} else {
    echo "Помилка додавання запису: " . $conn->error . "\n";
}

$conn->close();
