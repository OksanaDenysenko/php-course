<?php

$request = file_get_contents('php://input');
$text = json_decode($request, true);

$id = $text["id"];

$fileJson = json_decode(file_get_contents("file.json"), true);

$fileJson["items"][$id]["text"] = $text["text"];
$fileJson["items"][$id]["checked"] = $text["checked"];

file_put_contents("file.json", json_encode($fileJson, JSON_UNESCAPED_UNICODE));

echo json_encode(['ok' => true]);
