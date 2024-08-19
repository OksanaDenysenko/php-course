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
    $methodAndUri = explode(" ", $substrings[0]);
    $method = $methodAndUri[0];
    $uri = $methodAndUri[1];
    $body = $substrings[count($substrings) - 1];
    $partSubstringsForHeaders=array_splice($substrings, 1, -2);
    $headers = [];

    foreach ($partSubstringsForHeaders as $value) {
        $value_array = explode(":", $value);
        $headers[$value_array[0]] = $value_array[1];
    }

    return [
        "method" => $method,
        "uri" => $uri,
        "headers" => $headers,
        "body" => $body,
    ];
}

$http = parseTcpStringAsHttpRequest($contents);
echo(json_encode($http, JSON_PRETTY_PRINT));

