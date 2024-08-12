<?php

require 'config.php';

$config = require 'config.php';

// Create a connection to MySQL
$conn = new mysqli($config['dbhost'], $config['dbuser']);

// Check the connection
if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}

//Create database "level2"
$dbname = "level2";
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";

if ($conn->query($sql) === TRUE) {
    echo "Database 'level2' exists" . PHP_EOL;
} else {
    echo "Error creating database: " . $conn->error . PHP_EOL;
}

// Select the database
$conn->select_db($dbname);

//Create table "items"
$sql = "CREATE TABLE IF NOT EXISTS items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    text VARCHAR(255) NOT NULL,
    checked BOOL NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "The items table exists" . PHP_EOL;
} else {
    echo "Error creating items table: " . $conn->error . PHP_EOL;
}

//Create table "users"
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(255) NOT NULL UNIQUE,
    pass VARCHAR(255) NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "The users table exists" . PHP_EOL;
} else {
    echo "Error creating users table: " . $conn->error . PHP_EOL;
}

// Close the connection
$conn->close();

