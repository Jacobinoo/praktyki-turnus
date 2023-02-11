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

    if(isset($response_json['is_conduct_grade'])) {
        if($response_json['is_conduct_grade'] != "") {
            if($response_json['is_conduct_grade'] == "1") {
                if(isset($response_json['grade']) && isset($response_json['userid']) && isset($response_json['assigned_to_courseid'])) 
                {
                    if($response_json['is_conduct_grade'] == "" || $response_json['userid'] == "" || $response_json['grade'] == "" || $response_json['assigned_to_courseid'] == "") {
                        echo json_encode([
                            'error' => 'Wszystkie pola muszą być wypełnione'
                        ]);
                        return http_response_code(400);
                    } else {
                        $connection = @new mysqli($hostname, $username, $password, $dbname);
                    if ($connection->connect_error) {
                        echo json_encode([
                            'error' => 'Nie można połączyć się z serwerem bazy danych'
                        ]);
                        return http_response_code(500);
                    }
                        $query = $connection->prepare("SELECT * from grades WHERE is_conduct_grade = 1 AND assigned_to_userid = ?");
                        $query->bind_param("s", $response_json['userid']);
                        $query->execute();
                        $query->store_result();
                        $rows = $query->num_rows;
                        $query->execute();
                        $result = $query->get_result()->fetch_all(MYSQLI_ASSOC);
                        if($rows >= 1) {
                            echo json_encode([
                                'status' => 'error',
                                'error'=> 'Uczeń ma już ocenę z zachowania. Możesz ją edytować, ale nie możesz dodać drugiej.'
                            ]);
                            $connection->close();
                            return http_response_code(500);
                            exit();
                        }
            
                        $query = $connection->prepare("INSERT INTO grades (grade, is_conduct_grade, assigned_to_userid, assigned_to_courseid) VALUES (?, 1, ?, ?);");
                        $query->bind_param("iss", $response_json['grade'], $response_json['userid'], $response_json['assigned_to_courseid']);
                        if($query->execute()) {
                            echo json_encode([
                                'status' => 'success'
                            ]);
                        } else {
                            echo json_encode([
                                'status' => 'error',
                                'error'=> 'Wystąpił błąd przy dodawaniu oceny z zachowania'
                            ]);
                            return http_response_code(500);
                        }
                        $connection->close();
                    
                        exit();
                    }
                }
            }
        }
    }
    if(!isset($response_json['subject_id']) || !isset($response_json['grade']) || !isset($response_json['userid']) || !isset($response_json['assigned_to_courseid'])) {
        echo json_encode([
            'error' => 'Wszystkie pola muszą być wypełnione'
        ]);
        return http_response_code(400);
    }
    if($response_json['subject_id'] == "" || $response_json['grade'] == "" || $response_json['userid'] == "" || $response_json['assigned_to_courseid'] == ""){
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
        $query = $connection->prepare("INSERT INTO grades (subject_id, grade, is_conduct_grade, assigned_to_userid, assigned_to_courseid) VALUES (?, ?, 0, ?, ?);");
        $query->bind_param("iiss", $response_json['subject_id'], $response_json['grade'], $response_json['userid'], $response_json['assigned_to_courseid']);
        if($query->execute()) {
            echo json_encode([
                'status' => 'success'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'error'=> 'Wystąpił błąd. Możliwe, że uczeń już ma ocenę z tego przedmiotu.'
            ]);
            return http_response_code(500);
        }
        $connection->close();
    }
}
elseif($_SERVER["REQUEST_METHOD"] == "DELETE") {
    if(!file_get_contents('php://input')) return http_response_code(500);
        $response_json = json_decode(file_get_contents('php://input'), true);
    
        if(!isset($response_json['id'])) {
            echo json_encode([
                'error' => 'Nie podano identyfikatora oceny'
            ]);
            return http_response_code(400);
        }
        if($response_json['id'] == "") {
            echo json_encode([
                'error' => 'Nie podano identyfikatora oceny'
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
            $query = $connection->prepare("DELETE FROM grades WHERE id = ?");
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
    if(!file_get_contents('php://input')) return http_response_code(400);
        $response_json = json_decode(file_get_contents('php://input'), true);
        if(!isset($response_json['userid'])) {
            echo json_encode([
                'error' => 'Wszystkie pola muszą być wypełnione'
            ]);
            return http_response_code(400);
        }
        if($response_json['userid'] == ""){
            echo json_encode([
                'error' => 'Wszystkie pola muszą być wypełnione'
            ]);
            return http_response_code(500);
        }
        if(isset($response_json['is_conduct_grade'])) {
            if($response_json['is_conduct_grade'] == "true") {
                if(!isset($response_json['grade'])) {
                    echo json_encode([
                        'error' => 'Wszystkie pola muszą być wypełnione'
                    ]);
                    return http_response_code(400);
                }
                if($response_json['grade'] == ""){
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
                $connection = new mysqli($hostname, $username, $password, $dbname);
                if ($connection->connect_error) {
                    echo json_encode([
                        'error' => 'Nie można połączyć się z serwerem bazy danych'
                    ]);
                    return http_response_code(500);
                }
                $query = $connection->prepare("SELECT * FROM grades WHERE is_conduct_grade = 1 AND assigned_to_userid = ?");
                $query->bind_param("s", $response_json['userid']);
                $query->execute();
                $query->store_result();
                $rows = $query->num_rows;
                $query->execute();
                $result = $query->get_result()->fetch_all(MYSQLI_ASSOC);
                if($rows < 1) {
                    echo json_encode([
                        'status' => 'error',
                        'error'=> 'Wystąpił błąd przy edytowaniu oceny z zachowania'
                    ]);
                    $connection->close();
                    return http_response_code(500);
                    exit();
                } else {
                    $query = $connection->prepare("UPDATE grades SET grade = ? WHERE assigned_to_userid = ? AND is_conduct_grade = 1");
                    $query->bind_param("is", $response_json['grade'], $response_json['userid']);
    
                    if($query->execute()) {
                        echo json_encode([
                            'status' => 'success'
                        ]);
                    } else {
                        echo json_encode([
                            'status' => 'error',
                            'error'=> 'Wystąpił błąd przy edytowaniu oceny z zachowania'
                        ]);
                        return http_response_code(500);
                    }
                    $connection->close();
                    exit();
                }
            }
        }
        if(!isset($response_json['grade']) || !isset($response_json['id'])) {
            echo json_encode([
                'error' => 'Wszystkie pola muszą być wypełnione'
            ]);
            return http_response_code(400);
        }
        if($response_json['grade'] == "" || $response_json['id'] == ""){
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
            $query = $connection->prepare("UPDATE grades SET grade = ? WHERE id = ?");
            $query->bind_param("is", $response_json['grade'], $response_json['id']);
            if($query->execute()) {
                echo json_encode([
                    'status' => 'success'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'error'=> 'Wystąpił błąd przy edytowaniu oceny'
                ]);
                return http_response_code(500);
            }
            $connection->close();
        }
}