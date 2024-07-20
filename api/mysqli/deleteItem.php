<?php
// database connection
global $conn;
require_once("db_conect.php");

//receives json  { id: 22 }
$jsonGet = file_get_contents('php://input');
$text = json_decode($jsonGet, true);

$id = $text["id"];
//$id=1; // для перевірки

// SQL-request to delete a record
$sql = "DELETE FROM items WHERE id = $id";

//Execution of the request
if ($conn->query($sql) === TRUE) {
    echo json_encode(['ok' => true]);
} else {
    echo "Error deleting record: " . $conn->error . PHP_EOL;
}

// Updating ID numbers
$sql_update = "UPDATE items SET id = id - 1 WHERE id > $id";

if ($conn->query($sql_update) === TRUE) {
    echo "ID numbers successfully updated" . PHP_EOL;
} else {
    echo "Error updating ID numbers: " . $conn->error . PHP_EOL;
}

$conn->close();
