<?php

$config = require 'config.php';

// Create a connection to MySQL
$conn = new mysqli($config['dbhost'], $config['dbuser']);

// Check the connection
if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}

//Create database "level2"
$dbname = "level2";
$sql = "CREATE DATABASE  $dbname";

echo ($conn->query($sql) === TRUE ? "The database 'level2' was created" :
        "Error creating database: {$conn->error}") . PHP_EOL;

// Select the database
$conn->select_db($dbname);

//Request to create tables
$arrCreateTables = [
    'items' => 'CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    text VARCHAR(255) NOT NULL,
    checked BOOL NOT NULL
)',
    'users' => 'CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(255) NOT NULL UNIQUE,
    pass VARCHAR(255) NOT NULL
)'
];

//Execution of requests to create tables
foreach ($arrCreateTables as $tableName => $query) {
    echo ($conn->query($query) === TRUE ? "The " . $tableName . " table was created" :
            "Error creating " . $tableName . " table: {$conn->error}") . PHP_EOL;
}

// Close the connection
$conn->close();
