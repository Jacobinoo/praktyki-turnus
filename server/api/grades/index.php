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
    if(!file_get_contents('php://input')) return http_response_code(400);
    $response_json = json_decode(file_get_contents('php://input'), true);
    if(!isset($response_json['subject_name']) || !isset($response_json['range_hours']) || !isset($response_json['grade']) || !isset($response_json['userid'])) {
        echo json_encode([
            'error' => 'Wszystkie pola muszą być wypełnione'
        ]);
        return http_response_code(400);
    }
    if($response_json['subject_name'] == "" || $response_json['range_hours'] == "" || $response_json['grade'] == "" || $response_json['userid'] == ""){
        echo json_encode([
            'error' => 'Wszystkie pola muszą być wypełnione'
        ]);
        return http_response_code(500);
    }
    if($response_json['grade'] < 1 || $response_json['grade'] > 6) {
        echo json_encode([
            'error' => 'Nieprawidłowa skala ocen'
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
        $query = $connection->prepare("INSERT INTO grades (subject_name, range_hours, grade, is_conduct_grade, assigned_to_userid) VALUES (?, ?, ?, 0, ?);");
        $query->bind_param("siis", $response_json['subject_name'], $response_json['range_hours'], $response_json['grade'], $response_json['userid']);
        if($query->execute()) {
            echo json_encode([
                'status' => 'success'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'error'=> 'Wystąpił błąd przy dodawaniu oceny'
            ]);
            return http_response_code(500);
        }
        $connection->close();
    }
}
elseif($_SERVER["REQUEST_METHOD"] == "GET") {
    if(!file_get_contents('php://input')) return http_response_code(400);
    $response_json = json_decode(file_get_contents('php://input'), true);
    if(!isset($response_json['id'])) {
        echo json_encode([
            'status' => 'error',
            'error'=> 'Nie podano identyfikatora'
        ]);
        return http_response_code(400);
    }
    if($response_json['id'] == "") {
        echo json_encode([
            'status' => 'error',
            'error'=> 'Nie podano identyfikatora'
        ]);
        return http_response_code(400);
    }
    $connection = @new mysqli($hostname, $username, $password, $dbname);
    if ($connection->connect_error) {
        echo json_encode([
            'error' => 'Nie można połączyć się z serwerem bazy danych'
        ]);
        return http_response_code(500);
    }
    $query = $connection->prepare("SELECT * FROM grades WHERE assigned_to_userid = ? ORDER BY subject_name ASC");
    $query->bind_param("s", $response_json['id']);
    $query->execute();
    $query->store_result();
    $rows = $query->num_rows;
    $query->execute();
    $result = $query->get_result()->fetch_all(MYSQLI_ASSOC);
    if($rows < 1) {
        echo json_encode([]);
    } else {
        echo json_encode([
            'status' => 'success',
            'grades' => $result
        ]);
    }
    $connection->close();
}
elseif($_SERVER["REQUEST_METHOD"] == "DELETE") {
    if(!file_get_contents('php://input')) return http_response_code(500);
        $response_json = json_decode(file_get_contents('php://input'), true);
    
        if(!isset($response_json['id']) || !isset($response_json['courseid'])) {
            echo json_encode([
                'error' => 'Nie podano identyfikatora ucznia'
            ]);
            return http_response_code(400);
        }
        if($response_json['id'] == "" || $response_json['courseid'] == "") {
            echo json_encode([
                'error' => 'Nie podano identyfikatora ucznia'
            ]);
            return http_response_code(400);
        }
        $connection = new mysqli($hostname, $username, $password, $dbname);
            if ($connection->connect_error) {
                echo json_encode([
                    'error' => 'Nie można połączyć się z serwerem bazy danych'
                ]);
                return http_response_code(500);
            }
            $query = $connection->prepare("DELETE FROM participants WHERE uuid = ? AND assigned_course = ?");
            $query->bind_param("ss", $response_json['id'], $response_json['courseid']);
            $query->execute();
            echo json_encode([
                'status' => 'success'
            ]);
            $connection->close();
}
elseif($_SERVER["REQUEST_METHOD"] == "PUT") {
    return http_response_code(501);
}
elseif($_SERVER["REQUEST_METHOD"] == "PATCH") {
    return http_response_code(501);
}