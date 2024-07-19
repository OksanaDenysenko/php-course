<?php

try {
    $readFile = file_get_contents("file.json"); // read file
} catch (Exception $e) { // if unable to read
    error_log("Error reading file: " . $e->getMessage(), 3);
    echo "Server error";
}

echo $readFile; // output the contents of the file

// Оскільки працюємо з json файлом, то я тут не використовувала json_encode() i json_decode().
// echo file_get_contents("file.json"); і так виводить в форматі json