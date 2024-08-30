<?php

try {
    $readFile = file_get_contents("file.json");
} catch (Exception $e) {
    error_log("Error reading file: " . $e->getMessage(), 3);

    echo "Server error";
}

// output the contents of the file
echo $readFile;

// Оскільки працюємо з json файлом, то я тут не використовувала json_encode() i json_decode().
// echo file_get_contents("file.json"); і так виводить в форматі json