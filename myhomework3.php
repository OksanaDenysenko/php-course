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
 * This function generates a response in HTTP protocol format.
 * @param $statuscode - HTTP response status code.
 * @param $statusmessage - HTTP response status message.
 * @param $headers - An array of additional headers.
 * @param $body - The body of the response message.
 * @return void - A response in HTTP protocol format
 */
function outputHttpResponse($statuscode, $statusmessage, $headers, $body)
{

    $response = "HTTP/1.1 " . $statuscode . $statusmessage . PHP_EOL;
    $response .= "Date: " . gmdate("D, d M Y H:i:s GMT") . PHP_EOL;
    $response .= "Server: Apache/2.2.14 (Win32)" . PHP_EOL;
    $response .= "Content-Length:" . strlen($body) . PHP_EOL;
    $response .= "Connection: Closed" . PHP_EOL;
    $response .= "Content-Type: text/html; charset=utf-8" . PHP_EOL;
    $response .= PHP_EOL;
    $response .= $body;

    echo $response;
}

/**
 * This function is designed to handle incoming HTTP requests of a specific format.
 * It analyzes the request method, URI, headers, and body, and based on this information, forms a response.
 * @param $method - HTTP method
 * @param $uri - а path to the resource on the server
 * @param $headers - an array of HTTP request headers
 * @param $body - a body of the HTTP request
 * @return void - a response
 */
function processHttpRequest($method, $uri, $headers, $body)
{
    $subUri = explode("=", $uri);
    $numbers = explode(",", $subUri[1]);

    if ($method !== "GET" || !str_contains($uri, "?nums=") || count($numbers) < 1 || $numbers[0] == '') {
        outputHttpResponse(400, "Bad Request", $headers, "not found");

        return;
    }

    if (!str_starts_with($uri, "/sum")) { //uri не починається з /sum
        outputHttpResponse(404, "Not Found", $headers, "not found");

        return;
    }

    $sum = array_sum($numbers); // якщо метод GET, числа розділені комами і є ?nums=
    outputHttpResponse(200, "OK", $headers, $sum);
}

/**
 * This function is designed to parse a string received and convert it into a data structure representing an HTTP request.
 * @param $string - a string received over a TCP connection
 * @return array - a data structure representing an HTTP request
 */
function parseTcpStringAsHttpRequest($string)
{
    $substring = explode("\n", $string);
    $method_and_uri = explode(" ", $substring[0]);
    $method = $method_and_uri[0];
    $uri = $method_and_uri[1];
    $headers = [];

    foreach ($substring as $key => $value) {

        if ($key > 0 && $key < count($substring) - 2) { // Перевірка індексу
            $value_array = explode(":", $value);
            $headers[$value_array[0]] = $value_array[1];
        }
    }

    $body = $substring[count($substring) - 1];

    return [
        "method" => $method,
        "uri" => $uri,
        "headers" => $headers,
        "body" => $body,
    ];
}

$http = parseTcpStringAsHttpRequest($contents);
processHttpRequest($http["method"], $http["uri"], $http["headers"], $http["body"]);

