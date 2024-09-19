<?php

//receives json { text: "..." }
$request = file_get_contents('php://input');
$text = json_decode($request, true);

// file to count id
if (!file_exists("counter.txt")) {
    file_put_contents("counter.txt", "");
}

$count = file_get_contents("counter.txt");
$id = $count + 1;

//writing to a file
$array = ['id' => strip_tags(htmlspecialchars($id)),
    'text' => strip_tags(htmlspecialchars($text['text'])),
    'checked' => "false"];

$arrayJson = json_encode($array);

//if the file to write does not exist
if (!file_exists("file.json")) {
    file_put_contents("file.json",null);
}

$fileJson = json_decode(file_get_contents("file.json"), true);

$fileJson["items"][] = $array;
file_put_contents("file.json", json_encode($fileJson, JSON_UNESCAPED_UNICODE));

$response = ["id" => $id];

echo json_encode($response);

// write down the following id
file_put_contents("counter.txt", $id);

