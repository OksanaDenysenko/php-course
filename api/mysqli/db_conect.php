<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "level2";

// Створити підключення до MySQL
$conn = new mysqli($dbhost, $dbuser,$dbpass,$dbname);


// Перевірити підключення
if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}
else{ echo "Підключення виконано";}
