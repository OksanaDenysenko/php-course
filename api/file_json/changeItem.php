<?php

//приймає json  { id: 22, text: "...", checked: true }
//$jsonGet = file_get_contents('change.json');
$jsonGet = file_get_contents('php://input');
$text = json_decode($jsonGet, true);

$id=$text["id"];

$fileJson = json_decode(file_get_contents("file.json"), true);
$i=$id-1;
$fileJson["items"][$i]["text"]=$text["text"];
$fileJson["items"][$i]["checked"]=$text["checked"];

file_put_contents("file.json",json_encode($fileJson, JSON_UNESCAPED_UNICODE));
echo json_encode(['ok' => true]);
