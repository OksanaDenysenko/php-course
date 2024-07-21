<?php
$dbhost = "localhost";
$dbuser = "root";
//$dbpass = "password";

// Create a connection to MySQL
$conn = new mysqli($dbhost, $dbuser);

// Check the connection
if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}

//Create database "level2"
$sql = "CREATE DATABASE level2";

if ($conn->query($sql) === TRUE) {
    echo "Database 'level2' successfully created.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Close the connection
$conn->close();
