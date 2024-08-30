<?php

$request = file_get_contents('php://input');
$text = json_decode($request, true);

$id = $text["id"];

$fileJson = json_decode(file_get_contents("file.json"), true);

//Validation json
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON data']);

    exit();
}

$fileJson["items"][$id]["text"] = strip_tags(htmlspecialchars($text["text"]));
$fileJson["items"][$id]["checked"] = strip_tags(htmlspecialchars($text["checked"]));

file_put_contents("file.json", json_encode($fileJson, JSON_UNESCAPED_UNICODE));

echo json_encode(['ok' => true]);
