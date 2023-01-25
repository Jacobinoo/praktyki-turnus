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
    $connection->close();
    $index = 1;
    foreach ($result_participants as $participant) {
        $html2 .= "<tr><td>{$index}</td><td style='text-transform: capitalize;'>".$participant['full_name']."</td><td>szkoła</td></tr>";
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
                <th colspan="3">Wynik klasyfikacji z przedmiotów</th>
                <th>Zachowanie</th>
            </tr>
            <tr>
                <td>język obcy zawodowy</td>
                <td>Technologia robót wykończeniowych</td>
                <td>Technologia robót wykończeniowych</td>
                <td>Zachowanie</td>
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