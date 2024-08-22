<?php
//receives json  { id: 22 }
$request = file_get_contents('php://input');
$text = json_decode($request, true);

$id = $text["id"];

$fileJson = json_decode(file_get_contents("file.json"), true);

unset($fileJson["items"][$id]); // delete item

$count = file_get_contents("counter.txt");
file_put_contents("counter.txt", $count - 1);

file_put_contents("file.json", json_encode($fileJson, JSON_UNESCAPED_UNICODE));

echo json_encode(['ok' => true]);
