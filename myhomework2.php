<?php

// не звертайте на цю функцію уваги
// вона потрібна для того щоб правильно зчитати вхідні дані
function readHttpLikeInput() {
    $f = fopen( 'php://stdin', 'r' );
    $store = "";
    $toread = 0;
    while( $line = fgets( $f ) ) {
        $store .= preg_replace("/\r/", "", $line);
        if (preg_match('/Content-Length: (\d+)/',$line,$m))
            $toread=$m[1]*1;
        if ($line == "\r\n")
            break;
    }
    if ($toread > 0)
        $store .= fread($f, $toread);
    return $store;
}

$contents = readHttpLikeInput();

function parseTcpStringAsHttpRequest($string) {
    //$substring = explode(PHP_EOL, $string);
    $substring = explode("\n",$string);
    $method_and_uri = explode(" ",$substring[0]);
    $method=$method_and_uri[0];
    $uri=$method_and_uri[1];
    $headers=[];

//    for($i=1; $i<count($substring)-2; $i++){
//        $value_array=explode(":",$substring[$i]);
//        $headers[$value_array[0]]=$value_array[1];
//    }
    foreach ($substring as $key => $value) {
        if ($key > 0 && $key < count($substring) - 2) { // Перевірка індексу
            $value_array = explode(":", $value);
            $headers[$value_array[0]] = $value_array[1];
        }
    }

    $body=$substring[count($substring)-1];

    return array(
        "method" => $method,
        "uri" => $uri,
        "headers" => $headers,
        "body" => $body,
    );
}

$http = parseTcpStringAsHttpRequest($contents);
echo(json_encode($http, JSON_PRETTY_PRINT));

