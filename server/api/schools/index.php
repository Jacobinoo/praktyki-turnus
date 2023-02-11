<?php
/****************************/
/* Copyright 2023.
/* Owners: Jakub Banasiewicz, Patryk Kubik.
/* Permission granted for Zespół Szkoł im. Stanisława Staszica Koszarowa 7 28-200 Staszów, Poland.
/* More info inside LICENSE file.
/****************************/

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
    $connection = @new mysqli($hostname, $username, $password, $dbname);
        if ($connection->connect_error) {
            echo json_encode([
                'error' => 'Nie można połączyć się z serwerem bazy danych'
            ]);
            return http_response_code(500);
        }
        $query = $connection->prepare("SELECT * FROM schools ORDER BY name ASC");
        if($query->execute()) {
            $result = $query->get_result()->fetch_all(MYSQLI_ASSOC);
            echo json_encode([
                'status' => 'success',
                'schools' => $result
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'error'=> 'Wystąpił błąd przy pobieraniu danych o szkołach'
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
    if(!isset($response_json['name']) || !isset($response_json['address'])) {
        echo json_encode([
            'status' => 'error',
            'error'=> 'Nie wszystkie pola są wypełnione'
        ]);
        return http_response_code(400);
    }
    if($response_json['name'] == "" || $response_json['address'] == "") {
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
        $query = $connection->prepare("INSERT INTO schools (name, address) VALUES (?, ?)");
        $query->bind_param("ss", $response_json['name'], $response_json['address']);
        if($query->execute()) {
            echo json_encode([
                'status' => 'success'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'error'=> 'Wystąpił błąd przy dodawaniu szkoły'
            ]);
            http_response_code(500);
        }
        $connection->close();
}
elseif($_SERVER["REQUEST_METHOD"] == "DELETE") {
    if(!file_get_contents('php://input')) return http_response_code(500);
        $response_json = json_decode(file_get_contents('php://input'), true);
    
        if(!isset($response_json['id'])) {
            echo json_encode([
                'error' => 'Nie podano identyfikatora szkoły'
            ]);
            return http_response_code(400);
        }
        if($response_json['id'] == "") {
            echo json_encode([
                'error' => 'Nie podano identyfikatora szkoły'
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
            $query = $connection->prepare("DELETE FROM schools WHERE id = ?");
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