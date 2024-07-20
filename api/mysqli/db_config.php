<?php
$dbhost = "localhost";
$dbuser = "root";
//$dbpass = "password";

// Створити підключення до MySQL
$conn = new mysqli($dbhost, $dbuser);

// Перевірити підключення
if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

// Створити базу даних "level2"
$sql = "CREATE DATABASE level2";

if ($conn->query($sql) === TRUE) {
    echo "База даних 'level2' успішно створена.<br>";
} else {
    echo "Помилка створення бази даних: " . $conn->error . "<br>";
}

// Закрити підключення
$conn->close();
