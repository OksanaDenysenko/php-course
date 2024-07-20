<?php

global $db;
require_once("db_conect.php");

// Execute the query and get the results
$sql = "SELECT * FROM items";
$stmt = $db->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check for results
if (count($results) > 0) {
    // Convert data to JSON
    $json = json_encode(['items' => $results]);
    echo $json;
} else {
    echo "No results found";
}

// Close the database connection
$db = null;
