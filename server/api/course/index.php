<?php
session_start();
if(!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] == "") {
    echo json_encode([
        'error' => 'You are not logged in.'
    ]);
    return http_response_code(403);
}
header('Access-Control-Allow-Origin: http://127.0.0.1');
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Headers: Content-Type');
require_once '/xampp/htdocs/praktyki-turnus/server/connect.php';
mysqli_report(MYSQLI_REPORT_STRICT);

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
            $connection = new mysqli($hostname, $username, $password, $dbname);
            if (!$connection) {
                die(json_encode(["error"=>mysqli_connect_error()]));
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
    if(isset($_GET['id']) && $_GET['id'] !== "") {
        //Fetch given id's course details
        $courseId = $_GET['id'];
        $connection = new mysqli($hostname, $username, $password, $dbname);
        if (!$connection) {
            die(json_encode(["error"=>mysqli_connect_error()]));
        }
        $query = $connection->prepare("SELECT * FROM courses WHERE uuid = ?");
        $query->bind_param("s", $courseId);
        $query->execute();
        $query->store_result();
        $rows = $query->num_rows;

        $query->execute();
        $result = $query->get_result()->fetch_assoc();
        $connection->close();
        if($rows<1) {
            echo json_encode([]);
        } else {
            echo json_encode([
                "status" => "success",
                "course" => $result
            ]);
        }
        return http_response_code(501);
    } elseif(!isset($_GET['id']) || $_GET['id'] == "") {
        //Fetch all courses
        $connection = new mysqli($hostname, $username, $password, $dbname);
        if (!$connection) {
            die(json_encode(["error"=>mysqli_connect_error()]));
        }
        $query = $connection->prepare("SELECT * FROM courses");
        $query->execute();
        $query->store_result();
        $rows = $query->num_rows;

        $query->execute();
        $result = $query->get_result()->fetch_assoc();
        $connection->close();
        if($rows<1) {
            echo json_encode([]);
        } else {
            echo json_encode([
                "status" => "success",
                "courses" => Array($result)
            ]);
        }
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