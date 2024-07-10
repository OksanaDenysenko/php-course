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

    if ($method!="POST" || $headers["Content-Type"]!=" application/x-www-form-urlencoded"|| $uri!="/api/checkLoginAndPassword"){
        return outputHttpResponse(400," Bad Request",$headers,"not found");
    }
    else {
        $arrayBody = explode("&", $body);
        $login=explode("=", $arrayBody[0]);
        $password=explode("=", $arrayBody[1]);
        $login_password=$login[1].":".$password[1]; // отримали логін і пароль

        $file="D:\Сейчас\PHP\Level1\passwords.txt";
        if (!file_exists("passwords.txt")){ //якщо файлу не існує
            return outputHttpResponse(500," Integral Server Error",$headers,"not found");
        }
        else{
            try {
                $fileContent = file_get_contents($file); // читаємо файл
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        $string = explode("\n", $fileContent);
        for($i=0; $i<count($string); $i++){
            if($string[$i]===$login_password){
                return outputHttpResponse(200," OK",$headers,"<h1 style=\"color:green\">FOUND</h1>");
            }
            if($i===(count($string)-1)){ // якщо логін і пароль не знайдені в файлику
                return outputHttpResponse(401," Unauthorized",$headers,"not found");
            }

        }
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
