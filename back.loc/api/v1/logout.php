<?php

//CORS
header("Access-Control-Allow-Origin: http://front.loc");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

try {
    session_start(); // check if a session exists
    session_unset(); // clear all session variables
    session_destroy(); // destroy the session

    echo json_encode(['ok' => 'true']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
