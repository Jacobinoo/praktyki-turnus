<?php
header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Headers: Content-Type');
if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(!isset($_POST['login_code']) || $_POST['login_code'] == "") {
        print_r($_POST);
    } else {
        echo json_encode([
            'status' => 'ok'
        ]);
    }
}
elseif($_SERVER["REQUEST_METHOD"] == "GET") {
    return http_response_code(501);
}