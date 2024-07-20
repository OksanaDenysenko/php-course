<?php
// database connection
global $conn;
$dbname = "level2";
require_once("db_conect.php");

//receives json { text: "..." }
$jsonGet = file_get_contents('php://input');
$text = json_decode($jsonGet, true);

// checking whether the items table exists
$sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$dbname' AND TABLE_NAME = 'items'";
$result = $conn->query($sql);

if ($result->num_rows == 0) { //if does not exist, create a table
    $sql = "CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    text VARCHAR(255) NOT NULL,
    checked BOOL NOT NULL
)";
    if ($conn->query($sql) === TRUE) {
        echo "The items table was created successfully!" . PHP_EOL;
    } else {
        echo "Error creating table: " . $conn->error . PHP_EOL;
    }
}

//add item
$sql = "INSERT INTO items (text, checked) VALUES ('$text', FALSE)";
if ($conn->query($sql) === TRUE) {
    $id = $conn->insert_id;
    echo json_encode(['id' => $id]);
} else {
    echo "Error adding item: " . $conn->error . PHP_EOL;
}

$conn->close();
