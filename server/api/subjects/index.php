<?php
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

if($_SERVER["REQUEST_METHOD"] == "GET") {
    if(!file_get_contents('php://input')) {
        echo json_encode([
            'status' => 'error',
            'error'=> 'Nie przekazano żadnych danych'
        ]);
        return http_response_code(400);
    }
    $response_json = json_decode(file_get_contents('php://input'), true);
    if(!isset($response_json['assigned_to_courseid'])) {
        echo json_encode([
            'status' => 'error',
            'error'=> 'Nie podano identyfikatora turnusu'
        ]);
        return http_response_code(400);
    }
    if($response_json['assigned_to_courseid'] == "") {
        echo json_encode([
            'status' => 'error',
            'error'=> 'Nie podano identyfikatora turnusu'
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
        $query = $connection->prepare("SELECT * FROM subjects WHERE assigned_to_courseid = ? ORDER BY name ASC");
        $query->bind_param("s", $response_json['assigned_to_courseid']);
        if($query->execute()) {
            $result = $query->get_result()->fetch_all(MYSQLI_ASSOC);
            echo json_encode([
                'status' => 'success',
                'subjects' => $result
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'error'=> 'Wystąpił błąd przy pobieraniu danych o przedmiotach'
            ]);
            http_response_code(500);
        }
        $connection->close();
}
elseif($_SERVER["REQUEST_METHOD"] == "POST") {
    if(!file_get_contents('php://input')) {
        echo json_encode([
            'status' => 'error',
            'error'=> 'Nie przekazano żadnych danych'
        ]);
        return http_response_code(400);
    }
    $response_json = json_decode(file_get_contents('php://input'), true);
    if(!isset($response_json['name']) || !isset($response_json['assigned_to_courseid']) || !isset($response_json['range_hours'])) {
        echo json_encode([
            'status' => 'error',
            'error'=> 'Nie wszystkie pola są wypełnione'
        ]);
        return http_response_code(400);
    }
    if($response_json['name'] == "" || $response_json['assigned_to_courseid'] == "" || $response_json['range_hours'] == "") {
        echo json_encode([
            'status' => 'error',
            'error'=> 'Nie wszystkie pola są wypełnione'
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
        $query = $connection->prepare("INSERT INTO subjects (name, assigned_to_courseid, range_hours) VALUES (?, ?, ?)");
        $query->bind_param("ssi", $response_json['name'], $response_json['assigned_to_courseid'], $response_json['range_hours']);
        if($query->execute()) {
            echo json_encode([
                'status' => 'success'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'error'=> 'Wystąpił błąd przy dodawaniu przedmiotu'
            ]);
            http_response_code(500);
        }
        $connection->close();
}
elseif($_SERVER["REQUEST_METHOD"] == "DELETE") {
    if(!file_get_contents('php://input')) return http_response_code(500);
        $response_json = json_decode(file_get_contents('php://input'), true);
    
        if(!isset($response_json['id']) || !isset($response_json['assigned_to_courseid'])) {
            echo json_encode([
                'error' => 'Nie podano identyfikatorów'
            ]);
            return http_response_code(400);
        }
        if($response_json['id'] == "" || $response_json['assigned_to_courseid'] == "") {
            echo json_encode([
                'error' => 'Nie podano identyfikatorów'
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
            $query = $connection->prepare("DELETE FROM subjects WHERE id = ? AND assigned_to_courseid = ?");
            $query->bind_param("ss", $response_json['id'], $response_json['assigned_to_courseid']);
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