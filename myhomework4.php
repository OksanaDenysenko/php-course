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
 * @return void
 */
function processHttpRequest($method, $uri, $headers, $body)
{
    if ($method != "POST" || $headers["Content-Type"] != " application/x-www-form-urlencoded" || $uri != "/api/checkLoginAndPassword") {
        outputHttpResponse(400, " Bad Request", $headers, "not found");

        return;
    }

    $arrayBody = explode("&", $body);
    $login = explode("=", $arrayBody[0]);
    $password = explode("=", $arrayBody[1]);
    $login_password = $login[1] . ":" . $password[1]; // отримали логін і пароль

    $file = "passwords.txt";

    try {
        $fileContent = file_get_contents($file); // читаємо файл
    } catch (Exception $e) {
        error_log("Error reading file: " . $e->getMessage(), 3);
        outputHttpResponse(500, " Integral Server Error", $headers, "not found");

        return;
    }

    $string = explode("\n", $fileContent);

    foreach ($string as $value) {

        if ($value== $login_password) {
            outputHttpResponse(200, " OK", $headers, "<h1 style=\"color:green\">FOUND</h1>");

            return;
        }
    }

    outputHttpResponse(401, " Unauthorized", $headers, "not found");
}

/**
 * This function is designed to parse a string received and convert it into a data structure representing an HTTP request.
 * @param $string - a string received over a TCP connection
 * @return array - a data structure representing an HTTP request
 */
function parseTcpStringAsHttpRequest($string)
{
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
processHttpRequest($http["method"], $http["uri"], $http["headers"], $http["body"]);

