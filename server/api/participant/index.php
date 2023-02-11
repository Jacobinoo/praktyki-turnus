<?php
/****************************/
/* Copyright 2023.
/* Owners: Jakub Banasiewicz, Patryk Kubik.
/* Permission granted for Zespół Szkoł im. Stanisława Staszica Koszarowa 7 28-200 Staszów, Poland.
/* More info inside LICENSE file.
/****************************/

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
    if(!isset($response_json['full_name']) || !isset($response_json['birth_date']) || !isset($response_json['birth_place']) || !isset($response_json['pesel']) || !isset($response_json['school_id']) || !isset($response_json['address']) || !isset($response_json['email']) || !isset($response_json['assigned_course'])) {
        echo json_encode([
            'error' => 'Wszystkie pola muszą być wypełnione'
        ]);
        return http_response_code(400);
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
        $query = $connection->prepare("INSERT INTO participants (uuid, full_name, birth_date, birth_place, pesel, school_id, address, email, assigned_course) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);");
        $query->bind_param("sssssisss", $uuid, $response_json['full_name'], $response_json['birth_date'], $response_json['birth_place'], $response_json['pesel'], $response_json['school_id'], $response_json['address'], $response_json['email'], $response_json['assigned_course']);
        if($query->execute()) {
            echo json_encode([
                'status' => 'success',
                'uuid'=> $uuid
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'error'=> 'Wystąpił błąd przy dodawaniu ucznia'
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
    $query = $connection->prepare("SELECT * FROM grades WHERE assigned_to_userid = ? ORDER BY subject_name ASC");
    $query->bind_param("s", $response_json['id']);
    if($query->execute()) {
        $result = $query->get_result()->fetch_all(MYSQLI_ASSOC);
        echo json_encode([
            'status' => 'success',
            'grades' => $result
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'error'=> 'Wystąpił błąd przy dodawaniu ucznia'
        ]);
        return http_response_code(500);
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

            //delete grades
            $query = $connection->prepare("DELETE FROM grades WHERE assigned_to_userid = ?");
            $query->bind_param("s", $response_json['id']);
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