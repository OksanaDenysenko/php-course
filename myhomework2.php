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
    $substring = explode("\n",$string);
    $firstSubstring = explode(" ",$substring[0]);
    $method=$firstSubstring[0];
    $uri=$firstSubstring[1];
    $headers=[];

    for($i=1; $i<count($substring)-2; $i++){
        $sub=explode(":",$substring[$i]);
        $headers[$sub[0]]=$sub[1];
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

