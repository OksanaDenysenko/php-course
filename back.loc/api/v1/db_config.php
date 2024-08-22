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
$sql = "CREATE DATABASE $dbname";

if ($conn->query($sql) === TRUE) {

    echo "The database 'level2' was created" . PHP_EOL;
} else {

    echo "Error creating database: " . $conn->error . PHP_EOL;
}

// Select the database
$conn->select_db($dbname);

//Request to create table "items"
$arrCreateTables['items']=["CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    text VARCHAR(255) NOT NULL,
    checked BOOL NOT NULL
)"];

//Request to create table "users"
$arrCreateTables['users']=["CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(255) NOT NULL UNIQUE,
    pass VARCHAR(255) NOT NULL
)"];

//Execution of requests to create tables
foreach ($arrCreateTables as $key =>$value){
    $sql = $value;
    if ($conn->query($sql) === TRUE) {

        echo "The ".$key." table was created" . PHP_EOL;
    } else {

        echo "Error creating ".$key." table: " . $conn->error . PHP_EOL;
    }
}

// Close the connection
$conn->close();
