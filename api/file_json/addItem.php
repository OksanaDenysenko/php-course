<?php

//receives json { text: "..." }
$jsonGet = file_get_contents('php://input');
$text = json_decode($jsonGet, true);

// file to count id
if (!file_exists("counter.txt")) {
    file_put_contents("counter.txt", "0");
}
$count = file_get_contents("counter.txt");
$id = $count + 1;

//writing to a file
$array = ['id' => $id,
    "text" => $text,
    "checked" => "false"];

$arrayJson = json_encode($array);

if (!file_exists("file.json")) {//if the file to write does not exist
    fopen("file.json", "w");
}

$fileJson = json_decode(file_get_contents("file.json"), true);

$fileJson["items"][] = $array;
file_put_contents("file.json", json_encode($fileJson, JSON_UNESCAPED_UNICODE));

$response = ["id" => $id];
echo json_encode($response);

file_put_contents("counter.txt", $id); // write down the following id

