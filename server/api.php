<?php
session_start();
header('Access-Control-Allow-Origin: http://127.0.0.1');
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Headers: Content-Type');

$dbname = 'turnusy';
$hostname = "localhost";
$username = "root";
$password = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(!file_get_contents('php://input')) return http_response_code(504);
    $response_json = json_decode(file_get_contents('php://input'), true);
    if(!isset($response_json['login_code']) || $response_json['login_code'] == "") {
        echo json_encode([
            'error' => 'Invalid login code'
        ]);
        return http_response_code(500);
    }
    else {
        $login_code = $response_json['login_code'];
        if(strlen($login_code) !== 6) {
            echo json_encode([
                'error' => 'Invalid login code'
            ]);
            return http_response_code(500);
        }
            mysqli_report(MYSQLI_REPORT_STRICT);
            $connection = new mysqli($hostname, $username, $password, $dbname);
            if (!$connection) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $query = $connection->prepare("SELECT login_code FROM code WHERE login_code = ? LIMIT 1");
            $login_code = $login_code;
            $query->bind_param("s", $login_code);
            $query->execute();
            $query->store_result();
            $rows = $query->num_rows;

            $query->execute();
            $result = $query->get_result();
            $code = $result->fetch_assoc();
            $connection->close();
            if($rows<1) {
                echo json_encode([
                    'error' => 'Login code is invalid'
                ]);
                return http_response_code(401);
            } else {
                $_SESSION['isLoggedIn'] = true;
                echo json_encode([
                    "status" => "success",
                    "desc" => "Session established. Logging in."
                ]);
            }
    }
}
elseif($_SERVER["REQUEST_METHOD"] == "GET") {
    switch ($_GET) {
        //TODO
    }
    if(isset($_GET['checkAuth'])) {
        if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] == true) {
            echo json_encode([
                "isAuthenticated" => true
            ]);
        } else {
            echo json_encode([
                "isAuthenticated" => false
            ]);
        }
    }
    if(isset($_GET['checkAuth'])) {
        if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] == true) {
            echo json_encode([
                "isAuthenticated" => true
            ]);
        } else {
            echo json_encode([
                "isAuthenticated" => false
            ]);
        }
    }
    if(isset($_GET['logout'])) {
        session_destroy();
        echo json_encode([
            "status" => 'success'
        ]);
    }
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