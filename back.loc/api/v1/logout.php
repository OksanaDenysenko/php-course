<?php

//CORS
header("Access-Control-Allow-Origin: http://front.loc");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

try {
    session_start(); // перевірка, чи існує сесія
    session_unset(); // очищення всіх змінних сесії
    session_destroy(); // руйнування сесії
    echo json_encode(['ok' => 'true']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
