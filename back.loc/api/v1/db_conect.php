<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "level2";

// Create a connection to MySQL
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);


//// Check the connection // Прибрала, через помилку в браузері
//if ($conn->connect_error) {
//    die("Connection error: " . $conn->connect_error);
//} else {
//    echo "The connection is made";
//}
