<?php
session_start();
header('Access-Control-Allow-Origin: http://127.0.0.1');
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Headers: Content-Type');

if($_SERVER["REQUEST_METHOD"] == "GET") {
    session_destroy();
    echo json_encode([
        "status" => 'success'
    ]);
}
elseif($_SERVER["REQUEST_METHOD"] == "POST") {
    return http_response_code(501);
}
elseif($_SERVER["REQUEST_METHOD"] == "DELETE") {
    return http_response_code(501);
}
elseif($_SERVER["REQUEST_METHOD"] == "PUT") {
    return http_response_code(501);
}
elseif($_SERVER["REQUEST_METHOD"] == "PATCH") {
    return http_response_code(501);
}