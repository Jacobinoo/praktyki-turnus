<?php
session_start();
header('Access-Control-Allow-Origin: http://127.0.0.1');
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Headers: Content-Type');
require_once '/xampp/htdocs/praktyki-turnus/server/connect.php';


if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(!file_get_contents('php://input')) return http_response_code(504);
    $response_json = json_decode(file_get_contents('php://input'), true);
    if(!isset($response_json['login_code']) || $response_json['login_code'] == "") {
        echo json_encode([
            'error' => 'Nieprawidłowy kod logowania'
        ]);
        return http_response_code(500);
    }
    else {
        $login_code = $response_json['login_code'];
        if(strlen($login_code) !== 6) {
            echo json_encode([
                'error' => 'Nieprawidłowy kod logowania'
            ]);
            return http_response_code(500);
        }
            mysqli_report(MYSQLI_REPORT_OFF);
            $connection = @new mysqli($hostname, $username, $password, $dbname);
            if ($connection->connect_error) {
                echo json_encode([
                    'error' => 'Nie można połączyć się z serwerem bazy danych'
                ]);
                return http_response_code(500);
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
                    'error' => 'Nieprawidłowy kod logowania'
                ]);
                return http_response_code(401);
            } else {
                $_SESSION['isLoggedIn'] = true;
                echo json_encode([
                    "status" => "success",
                    "desc" => "Sesja ustanowiona. Logowanie..."
                ]);
            }
    }
}
elseif($_SERVER["REQUEST_METHOD"] == "GET") {
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