<?php
//приймає json  { id: 22 }
$jsonGet = file_get_contents('php://input');
$text = json_decode($jsonGet, true);

//$id=$text["id"];
$id=1;

$fileJson = json_decode(file_get_contents("file.json"), true);
//$i=$id-1;
unset($fileJson["items"][$id-1]); // видалення
$count = file_get_contents("counter.txt");
$id=$count-1;
file_put_contents("counter.txt", $id);

for ($i=$id; $i<count($fileJson["items"]); $i++){ // здвиг id на 1
    $fileJson["items"][$i]["id"]=$fileJson["items"][$i]["id"]-1;
}
//переіндексація
$fileJson["items"] = array_values($fileJson["items"]);


file_put_contents("file.json",json_encode($fileJson, JSON_UNESCAPED_UNICODE));
echo json_encode(['ok' => true]);
