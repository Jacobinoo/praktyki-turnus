<?php
require_once '/xampp/htdocs/praktyki-turnus/server/vendor/autoload.php';
use Ramsey\Uuid\Uuid;
session_start();
if(!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] == "") {
    echo json_encode([
        'error' => 'Nie jesteś zalogowany(a)'
    ]);
    return http_response_code(403);
}
header('Access-Control-Allow-Origin: http://127.0.0.1');
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Headers: Content-Type');
require_once '/xampp/htdocs/praktyki-turnus/server/connect.php';
mysqli_report(MYSQLI_REPORT_OFF);

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(!file_get_contents('php://input')) return http_response_code(504);
    $response_json = json_decode(file_get_contents('php://input'), true);
    if(!isset($response_json['full_name']) || !isset($response_json['birth_date']) || !isset($response_json['birth_place']) || !isset($response_json['pesel']) || !isset($response_json['school_id']) || !isset($response_json['address']) || !isset($response_json['email']) || !isset($response_json['assigned_course'])) {
        echo json_encode([
            'error' => 'Wszystkie pola muszą być wypełnione'
        ]);
        return http_response_code(500);
    }
    if($response_json['full_name'] == "" || $response_json['birth_date'] == "" || $response_json['birth_place'] == "" || $response_json['pesel'] == "" || $response_json['school_id'] == "" || $response_json['address'] == "" || $response_json['email'] == "" || $response_json['assigned_course'] == ""){
        echo json_encode([
            'error' => 'Wszystkie pola muszą być wypełnione'
        ]);
        return http_response_code(500);
    }
    if(strlen($response_json['pesel']) != 11) {
        echo json_encode([
            'error' => 'PESEL musi mieć 11 cyfr'
        ]);
        return http_response_code(500);
    }
    else {
        $connection = @new mysqli($hostname, $username, $password, $dbname);
        if ($connection->connect_error) {
            echo json_encode([
                'error' => 'Nie można połączyć się z serwerem bazy danych'
            ]);
            return http_response_code(500);
        }
        $uuid = Uuid::uuid4();
        $query = $connection->prepare("INSERT INTO courses (uuid, code, class, start_date, end_date) VALUES (?, ?, ?, ?, ?);");
        $query->bind_param("sissss", $uuid, $response_json['course_code'], $response_json['course_class'], $response_json['start_date'], $response_json['end_date']);
        $query->execute();
        echo json_encode([
            'status' => 'success',
            'uuid'=> $uuid
        ]);
        $connection->close();
    }
}
elseif($_SERVER["REQUEST_METHOD"] == "GET") {
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