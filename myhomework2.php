<?php

/**
 * This function simulates reading data from standard input in a format that resembles an HTTP request.
 * @return string
 */
function readHttpLikeInput()
{
    $f = fopen('php://stdin', 'r');
    $store = "";
    $toread = 0;
    while ($line = fgets($f)) {
        $store .= preg_replace("/\r/", "", $line);
        if (preg_match('/Content-Length: (\d+)/', $line, $m))
            $toread = $m[1] * 1;
        if ($line == "\r\n")
            break;
    }
    if ($toread > 0)
        $store .= fread($f, $toread);
    return $store;
}

$contents = readHttpLikeInput();

/**
 * This function is designed to parse a string received and convert it into a data structure representing an HTTP request.
 * @param $string - a string received over a TCP connection
 * @return array - a data structure representing an HTTP request
 */
function parseTcpStringAsHttpRequest($string)
{
    //$substring = explode(PHP_EOL, $string);
    $substrings = explode("\n", $string);
    $method_and_uri = explode(" ", $substrings[0]);
    $method = $method_and_uri[0];
    $uri = $method_and_uri[1];
    $headers = [];

    foreach ($substrings as $key => $value) {
        if ($key > 0 && $key < count($substrings) - 2) { // Перевірка індексу
            $value_array = explode(":", $value);
            $headers[$value_array[0]] = $value_array[1];
        }
    }

    $body = $substrings[count($substrings) - 1];

    return [
        "method" => $method,
        "uri" => $uri,
        "headers" => $headers,
        "body" => $body,
    ];
}

$http = parseTcpStringAsHttpRequest($contents);
echo(json_encode($http, JSON_PRETTY_PRINT));

