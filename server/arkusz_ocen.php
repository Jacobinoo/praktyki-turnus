<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();
if(!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] == "") {
    echo json_encode([
        'error' => 'Nie jesteś zalogowany(a)'
    ]);
    return http_response_code(403);
}
if(!isset($_GET['id']) || !isset($_GET['name'])) {
    http_response_code(400);
    exit('{"error": "Bad Request 400"}');
}
if($_GET['id'] == "" || $_GET['name'] == "") {
    http_response_code(400);
    exit('{"error": "Bad Request 400"}');
}
define("CURRENT_DATE_PLACE", "Staszów, ".date("d.m.Y H:i"));
header('Access-Control-Allow-Origin: http://127.0.0.1');
header('Access-Control-Allow-Headers: Content-Type');
$mpdf = new \Mpdf\Mpdf();
$mpdf->SetTitle('Pogląd');
$stylesheet = file_get_contents('arkusz_ocen.css');
$mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);
$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML(test(),\Mpdf\HTMLParserMode::HTML_BODY);
function test() {
    $course_name = $_GET['name'];
    $uuid = $_GET['id'];
    require __DIR__ .'/connect.php';
    $connection = new mysqli($hostname, $username, $password, $dbname);
    if (!$connection) {
        die(json_encode(["error"=>mysqli_connect_error()]));
    }
    $query = $connection->prepare("SELECT * FROM participants WHERE assigned_course = ?");
    $query->bind_param("s", $uuid);
    $query->execute();
    $query->store_result();
    $rows = $query->num_rows;
    $query->execute();
    if($rows < 1) {
        $connection->close();
        http_response_code(500);
        exit('{"error": "Nie znaleziono ucznia"}');
    }
    $result_participants = $query->get_result()
    ->fetch_all(MYSQLI_ASSOC);
    $query = $connection->prepare("SELECT * FROM courses WHERE uuid = ?");
    $query->bind_param("s", $uuid);
    $query->execute();
    $query->store_result();
    $rows = $query->num_rows;
    $query->execute();
    if($rows < 1) {
        $connection->close();
        http_response_code(500);
        exit('{"error": "Nie znaleziono ucznia"}');
    }
    $course = $query->get_result()
    ->fetch_assoc();

        //schools list
        $query = $connection->prepare("SELECT * FROM schools ORDER BY id ASC");
        $query->execute();
        $query->store_result();
        $rows_schools = $query->num_rows;
        $query->execute();
        if($rows_schools < 1) {
            $result_schools = [];
        }
        $result_schools = $query->get_result()->fetch_all(MYSQLI_ASSOC);

        //Subject list
        $query = $connection->prepare("SELECT * FROM subjects WHERE assigned_to_courseid = ? ORDER BY id ASC");
        $query->bind_param("s",  $uuid);
        $query->execute();
        $query->store_result();
        $rows_subjects = $query->num_rows;
        $query->execute();
        if($rows_subjects < 1) {
            $result_subjects = [];
        }
        $result_subjects = $query->get_result()->fetch_all(MYSQLI_ASSOC);

        //Grades list
        $query = $connection->prepare("SELECT * FROM grades ORDER BY subject_id ASC");
        $query->execute();
        $query->store_result();
        $rows_grades = $query->num_rows;
        $query->execute();
        if($rows_grades < 1) {
            $result_grades = [];
        }
        $result_grades = $query->get_result()->fetch_all(MYSQLI_ASSOC);

    $connection->close();
    $index = 1;
    error_reporting(0);
    foreach($result_subjects as $subject) {
        $przedmioty_html .= "<td>".$subject['name']."</td>";
    }
    echo "<pre>";
    print_r($result_subjects);
    print_r($result_grades);
    print_r($result_participants);
    echo "</pre>";
    foreach ($result_participants as $participant) {
        $key = array_search($participant['school_id'], array_column($result_schools, 'id'));
        foreach ($result_subjects as $subject) {
            $grades = array_search($subject['id'], array_column($result_grades, 'subject_id'));
            // $user_grade = array_search($participant['uuid'], array_column($grades, 'assigned_to_userid'));
            echo $grade;
        }
        $html2 .= "<tr><td>{$index}</td><td style='text-transform: capitalize;'>".$participant['full_name']."</td><td>".$result_schools[$key]['name']."</td><td></td></tr>";
        $index = $index + 1;
    }
    $html = '
    <div class="container">
    <div class="place-date">'.CURRENT_DATE_PLACE.'</div>
    <h4>ZESTAWIENIE WYNIKÓW KLASYFIKACJI</h4>
    <div class="range-date">Turnus od '.date("d.m.Y", strtotime($course['start_date'])).' do '.date("d.m.Y", strtotime($course['end_date'])).'</div>
    <div class="turnus-name">'.$course_name.'  <b>['.$course['code'].']</b>  KL. '.$course['class'].'</div>
        <table>
            <thead>
            <tr>
                <th rowspan="2" class="LP">Lp.</th>
                <th rowspan="2" width="100pt">Imię nazwisko </th>
                <th rowspan="2" width="80pt">Szkoła kierująca na turnus</th>
                <th colspan="'.$rows_subjects.'">Wynik klasyfikacji z przedmiotów</th>
                <th>Zachowanie</th>
            </tr>
            <tr>
            '.$przedmioty_html.'<td>zachowanie</td>
            </tr>
            </thead>
        <tbody>
        '.$html2.'
        </tbody>
        </table>
    </div>';
    return $html;
};


$mpdf->Output("Zestawienie_wyników_kwalifikacji_{$course_code}", 'I');