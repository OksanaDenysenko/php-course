<?php
//receives json  { id: 22 }
$request = file_get_contents('php://input');
$text = json_decode($request, true);

$id = $text["id"];

$fileJson = json_decode(file_get_contents("file.json"), true);

// delete item
foreach ($fileJson["items"] as $key=> $line) {
    if ($line['id']==$id) {
        unset($fileJson["items"][$key]);
    }
}

//or this variant

//$new_fileJson=[];
//
//foreach ($fileJson["items"] as $key=> $line) {
//    if ($line['id']!=$id) {
//        $new_fileJson[] = $fileJson["items"][$key];
//    }
//}
//
//$fileJson=$new_fileJson;

file_put_contents("file.json", json_encode($fileJson, JSON_UNESCAPED_UNICODE));

echo json_encode(['ok' => true]);
