<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php

if (!file_exists("counter.txt")) { // create a file for the counter
    file_put_contents("counter.txt", "0");
}

$count = file_get_contents("counter.txt"); // increase the counter with each visit to the page
$count++;

file_put_contents("counter.txt", $count);
echo $count;
?>

</body>
</html>

