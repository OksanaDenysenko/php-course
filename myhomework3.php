<?php

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

function outputHttpResponse($statuscode, $statusmessage, $headers, $body) {

    $response = "HTTP/1.1 " . $statuscode.$statusmessage.PHP_EOL;
    $response .= "Date: " . gmdate("D, d M Y H:i:s GMT").PHP_EOL;
    $response .="Server: Apache/2.2.14 (Win32)".PHP_EOL;
    $response .="Content-Length:".strlen($body). PHP_EOL;
    $response .="Connection: Closed".PHP_EOL;
    $response .="Content-Type: text/html; charset=utf-8".PHP_EOL;
    $response .= PHP_EOL;
    $response .= $body;

    echo $response;
}

function processHttpRequest($method, $uri, $headers, $body) {
    $subUri=explode("=",$uri);
    // $numbers=explode(",",$subUri[1]);

    if ($method==="GET"&& strpos($uri,"?nums=")!=false && $numbers=explode(",",$subUri[1])){ // якщо метод GET, числа розділені комами і є ?nums=
        if(strpos($uri,"/sum")!=0){ // uri не починається з /sum
            outputHttpResponse(404,"Not Found",$headers,"not found");
        }
        else {
            $sum=0;
            for($i=0; $i<count($numbers); $i++){
                $sum=$sum+$numbers[$i];
            }
            outputHttpResponse(200," OK",$headers,$sum);
        }
    }
    else {
        outputHttpResponse(400," Bad Request",$headers,"not found");
    }
}

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
processHttpRequest($http["method"], $http["uri"], $http["headers"], $http["body"]);

