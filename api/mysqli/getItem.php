<?php
global $conn;
require_once("db_conect.php"); // Використовуйте require_once, щоб уникнути повторного підключення

// Перевірка існування бази даних
$dbname = "level2";
$sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "База даних $dbname існує!\n";
} else {
    echo "База даних $dbname не існує.\n";
}

// Виконання запиту
$sql = "SELECT * FROM items";
$result = $conn->query($sql);

// Перевірка наявності результатів
if ($result->num_rows > 0) {
    // Створення масиву даних
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Перетворення масиву даних в JSON
    $json = json_encode(['items' => $data]);

    // Виведення JSON на екран
    echo $json;
} else {
    echo "Результатів не знайдено";
}

// Закриття з'єднання з базою даних
$conn->close();
