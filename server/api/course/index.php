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
//mysqli_report(MYSQLI_REPORT_OFF);

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(!file_get_contents('php://input')) return http_response_code(504);
    $response_json = json_decode(file_get_contents('php://input'), true);
    if(!isset($response_json['course_code']) || !isset($response_json['course_class']) || !isset($response_json['start_date']) || !isset($response_json['end_date'])) {
        echo json_encode([
            'error' => 'Wszystkie pola muszą być wypełnione'
        ]);
        return http_response_code(500);
    }
    if($response_json['course_code'] == "" || $response_json['course_class'] == "" || $response_json['start_date'] == "" || $response_json['end_date'] == ""){
        echo json_encode([
            'error' => 'Wszystkie pola muszą być wypełnione'
        ]);
        return http_response_code(500);
    }
    if(strlen($response_json['course_code']) > 6) {
        echo json_encode([
            'error' => 'Kod zawodu musi mieć nie więcej niż 6 cyfr'
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
            $query->bind_param("sssss", $uuid, $response_json['course_code'], $response_json['course_class'], $response_json['start_date'], $response_json['end_date']);
            $query->execute();
            echo json_encode([
                'status' => 'success',
                'uuid'=> $uuid
            ]);
            $connection->close();
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
        $rows_course = $query->num_rows;

        $query->execute();
        if($rows_course < 1) {
            $connection->close();
            echo json_encode([]);
            exit();
        }
        $result_course = $query->get_result()->fetch_assoc();

        //Participant table
        $query = $connection->prepare("SELECT * FROM participants WHERE assigned_course = ? ORDER BY full_name ASC");
        $query->bind_param("s", $courseId);
        $query->execute();
        $query->store_result();
        $rows_participants = $query->num_rows;
        $query->execute();
        $result_participants = $query->get_result()->fetch_all(MYSQLI_ASSOC);

        //Grades table
        $query = $connection->prepare("SELECT * FROM grades ORDER BY id ASC");
        $query->execute();
        $query->store_result();
        $rows_grades = $query->num_rows;
        $query->execute();
        $result_grades = $query->get_result()->fetch_all(MYSQLI_ASSOC);

        //Subjects table
        $query = $connection->prepare("SELECT * FROM subjects WHERE assigned_to_courseid = ? ORDER BY name ASC");
        $query->bind_param("s", $courseId);
        $query->execute();
        $query->store_result();
        $rows_subjects = $query->num_rows;
        $query->execute();
        $result_subjects = $query->get_result()->fetch_all(MYSQLI_ASSOC);


        // Schools table
        // $query = $connection->prepare("SELECT * FROM schools ORDER BY name ASC");
        // $query->execute();
        // $result_schools = $query->get_result()->fetch_all(MYSQLI_ASSOC);
        $connection->close();
        echo json_encode([
            "status" => "success",
            "course" => $result_course,
            "participants" => $rows_participants<1 ? [] : $result_participants,
            "grades" => $rows_grades<1 ? [] : $result_grades,
            "subjects" => $rows_subjects<1 ? [] : $result_subjects
            //"schools" => $result_schools
        ]);
    } elseif(!isset($_GET['id']) || $_GET['id'] == "") {
        //Fetch all courses
        $connection = @new mysqli($hostname, $username, $password, $dbname);
        if ($connection->connect_error) {
            echo json_encode([
                'error' => 'Nie można połączyć się z serwerem bazy danych'
            ]);
            return http_response_code(500);
        }
        $query = $connection->prepare("SELECT * FROM courses ORDER BY code");
        $query->execute();
        $result = $query->get_result();
        $result = $result->fetch_all(MYSQLI_ASSOC);
        $connection->close();
        echo json_encode([
            "status" => "success",
            "courses" => $result
        ]);
    }
}
elseif($_SERVER["REQUEST_METHOD"] == "DELETE") {
    if(!file_get_contents('php://input')) return http_response_code(500);
    $response_json = json_decode(file_get_contents('php://input'), true);

    if(!isset($response_json['id'])) {
        echo json_encode([
            'error' => 'Nie podano identyfikatora turnusu'
        ]);
        return http_response_code(400);
    }
    if($response_json['id'] == "" ) {
        echo json_encode([
            'error' => 'Nie podano identyfikatora turnusu'
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
        $query = $connection->prepare("DELETE FROM courses WHERE uuid = ?");
        $query->bind_param("s", $response_json['id']);
        $query->execute();
        $query = $connection->prepare("DELETE FROM participants WHERE assigned_course = ?");
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