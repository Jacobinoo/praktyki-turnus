<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();
if(!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] == "") {
    echo json_encode([
        'error' => 'Nie jesteś zalogowany(a)'
    ]);
    return http_response_code(403);
}
if(!isset($_GET['id']) || !isset($_GET['p']) || !isset($_GET['name'])) {
    http_response_code(400);
    exit('{"error": "Bad Request 400"}');
}
if($_GET['id'] == "" || $_GET['p'] == "" || $_GET['name'] == "") {
    http_response_code(400);
    exit('{"error": "Bad Request 400"}');
}
$uuid = $_GET['id'];
$pesel = $_GET['p'];
$course_name = $_GET['name'];
require_once '/xampp/htdocs/praktyki-turnus/server/connect.php';
header('Access-Control-Allow-Origin: http://127.0.0.1');
header('Access-Control-Allow-Headers: Content-Type');
$mpdf = new \Mpdf\Mpdf();
$stylesheet = file_get_contents('zaswiadczenie.css');
$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);

define("CURRENT_DATE_PLACE", "Staszów, ".date("d.m.Y"));

$year = date('Y');
$year = $year[-2].$year[-1];

if($_SERVER["REQUEST_METHOD"] == "GET") {
    $connection = new mysqli($hostname, $username, $password, $dbname);
    if (!$connection) {
        die(json_encode(["error"=>mysqli_connect_error()]));
    }
    $query = $connection->prepare("SELECT * FROM participants WHERE uuid = ? AND pesel = ?");
    $query->bind_param("ss", $uuid, $pesel);
    $query->execute();
    $query->store_result();
    $rows = $query->num_rows;
    $query->execute();
    if($rows < 1) {
        $connection->close();
        http_response_code(500);
        exit('{"error": "Nie znaleziono ucznia"}');
    }
    $result_participant = $query->get_result()->fetch_assoc();

    //Course data
    $query = $connection->prepare("SELECT * FROM courses WHERE uuid = ?");
    $query->bind_param("s", $result_participant['assigned_course']);
    $query->execute();
    $query->store_result();
    $rows = $query->num_rows;
    $query->execute();
    if($rows < 1) {
        $connection->close();
        http_response_code(500);
        exit('{"error": "Turnus przypisany do ucznia nie został znaleziony"}');
    }
    $result_course = $query->get_result()->fetch_assoc();

    //School list
    $query = $connection->prepare("SELECT * FROM schools WHERE id = ?");
    $query->bind_param("s", $result_participant['school_id']);
    $query->execute();
    $query->store_result();
    $rows = $query->num_rows;
    $query->execute();
    if($rows < 1) {
        $school = "Błąd - nieznana szkoła ~ nie istnieje w bazie danych";
    }
    $result_school = $query->get_result()->fetch_assoc();

    //Subject list
    $query = $connection->prepare("SELECT * FROM subjects WHERE assigned_to_courseid = ?");
    $query->bind_param("s",  $result_participant['assigned_course']);
    $query->execute();
    $query->store_result();
    $rows = $query->num_rows;
    $query->execute();
    if($rows < 1) {
        $result_subjects = [];
    }
    $result_subjects = $query->get_result()->fetch_all(MYSQLI_ASSOC);


    //Grades
    $query = $connection->prepare("SELECT * FROM grades WHERE assigned_to_userid = ?");
    $query->bind_param("s",  $uuid);
    $query->execute();
    $query->store_result();
    $rows = $query->num_rows;
    $query->execute();
    if($rows < 1) {
        $result_grades = [];
    }
    $result_grades = $query->get_result()->fetch_all(MYSQLI_ASSOC);
    $connection->close();

    function gradesRows($result_grades) {
        $html2 = "";
        $i = 0;
        foreach ($result_grades as $row) {
            $i = $i+1;
            $html2 .= "<tr>
            <td>{$i}</td>
            <td></td>
            <td></td>
            <td>".$row['grade']."</td>
            </tr>";
        }
        $html = '<div class="text">Oceny uzyskane z przedmiotów zawodowych teoretycznych objętych programem naucznia realizowanym na turnusie dokształcania teoretycznego młodocianych pracowników:</div>
    <table>
        <tr>
            <th>Lp.</th>
            <th>Nazwa zajęć</th>
            <th>Wymiar godzin zajęć</th>
            <th>Ocena<sup style="font-size: 6pt;">5)</sup></th>
        </tr>
        '.$html2.'
        <tr>
            <td>1</td>
            <td>Jezyk angielski to bardzo popularny jezyk, prawdopodobnie najpopularniejszy jezyk swiata</td>
            <td>15h</td>
            <td>5</td>
        </tr>
    </table>
    <div style="margin-top: 10pt;">
    <div class="grade-text">Ocena zachowania<sup style="font-size: 6pt;">6)</sup></div>
        <div class="grade-dots">test</div>
    </div>
    <div class="footer-2">
        <div class="line-2"></div>
        <div class="footer-text-2">5) Skala ocen: celujący, bardzo dobry, dostateczny, dopuszczający, niedostateczny.<br>
        6) Skala ocen: wzorowe, bradzo dobre, dobre, poprawne, nieodpowiednie, naganne.
        </div>
    </div>';
        return $html;
    };


    $names_surname = $result_participant['full_name'];
    $birth_date = date("d.m.Y", strtotime($result_participant['birth_date']));
    $birth_place = $result_participant['birth_place'];
    $pesel = $result_participant['pesel'];
    $course_code = $result_course['code'];
    $range = "klasa {$result_course['class']}";
    $school = $result_school['address'];
    $nr = "";

$mpdf->WriteHTML('<div class="container">
<div class="stump-box">
    <div class="stump"></div>
    <div class="stump-text">
        (pieczątka szkoły lub centrum kształcenia zawodowego)
    </div>
</div>
<div class="title-box">
    <div class="title-text">ZAŚWIADCZENIE</div>
    <div class="title-description">
        o ukończeniu dokształcania teoretycznego młodocianych pracowników
    </div>
</div>
<div class="text-box">
    <div class="names">
        <div style="width: 10pt;">Zaświadcza się, że Pan(i)</div>
        <div class="names-surname">'.$names_surname.'</div>
    </div>
    <div class="names-description">(imię/imiona i nazwisko)</div>
    <div class="personal-information">
        <div class="pi-description-birth-date">
            <div class="birth-date">'.$birth_date.'</div>
            <div class="pi-description-text">(data urodzenia)</div>
        </div>
        <div class="pi-description-birth-place">
            <div class="birth-place">'.$birth_place.'</div>
            <div class="pi-description-text">(miejsce urodzenia)</div>
        </div>
        <div class="pi-description-pesel">
            <div class="PESEL">'.$pesel.'</div>
            <div class="pi-description-text">
                (numer PESEL<sup style="font-size: 6pt">1)</sup>)
            </div>
        </div>
    </div>
    <div class="row-div">
        <div style="width: 336pt;float: left;">
            Ukończył(a) turnus dokształcania teoretycznego młodocianych
            pracowników w z zawodzie<sup style="font-size: 6pt">2)</sup>
        </div>
        <div class="dots1"></div>
    </div>
    <div class="row-div">
        <div class="dots2">'.$course_name.' '.$course_code.',</div>
    </div>
    <div class="row-div">
        <div style="width:46pt; float: left;">w zakresie<sup style="font-size: 6pt;">3)</sup></div>
        <div class="dots3">'.$range.'</div>
    </div>
    <div class="row-div">
        <div style="width: 68pt;float: left;">prowadzony przez</div>
        <div class="dots5">'.$school.'</div>
    
    <div class="last-line-description" style="text-align: center">
        <div class="place-information" style="text-align: center">
            (nazwa i adres szkoły lub centrum kształcenia zawodowego)
        </div>
        </div>
    </div>
    <div class="certificate-description">
        Zaświadczenie wydano na podstawie § 21 ust. 1 rozporządzenia
        Ministra Edukacji Narodowej z dnia 19 marca 2019 r. w sprawie
        kształcenia ustawicznego w formatach pozaszkolnych (Dz. U. poz.
        652).
    </div>
    <div class="current-date-place">
        <div class="cdp-dots">'.CURRENT_DATE_PLACE.'</div>
        <div>(miejscowość, data)</div>
    </div>
    <div class="nr">
        <div class="nr-text">Nr</div>
        <div class="nr-dots">'.$nr.'</div>
        <div class="nr-text2">/20</div>
        <div class="nr-dots2">'.$year.'</div>
        <div class="nr-text3">r.<sup style="font-size: 6pt">4)</sup></div>
    </div>
    <div class="stump-signature">
        <div class="ss-dots"></div>
        <div class="ss-description">
            (pieczątka i podpis dyrektora szkoły lub centrum kształcenia
            zawodowego)
        </div>
    </div>
</div>
<div class="footer">
    <div class="line"></div>
    <div class="footer-text">
        <div class="footer-row-div">
            1) W przypadku osoby, która nie posiada numeru PESEL, należy
            wpisać nazwę i numer dokumentu potwierdzającego jej tożsamość.
        </div>
        <div class="footer-row-div">
            2) Wpisać nazwę i symbol cyfrowy zawodu odopowiednio zgodnie z
            przepisami wydanymi na podstawie art. 46 ust. 1 ustawy z dnia 14
            grudnia 2016 r. - Prawo oświatowe (Dz. U. z 2018 r. poz. 996,
            1000, 1290, 1669, i 2245 oraz z 2019 r. poz. 534) albo przepisami
            wydanymi na podstawie art. 46 ust. 1 ustawy z dnia 14 grudnia 2016
            r. - Prawo oświatowe, w brzmieniu obowiązującym przed dniem 1
            września 2019 r.
        </div>
        <div class="footer-row-div">
            3) Wpisać realizowany na turnusie zakres dokształcania
            teoretycznego młodocianych pracowników.
        </div>
        <div class="footer-row-div">
            4) Wpisać numer ewidencji zaświadczeń prowadzonej przez szkołę lub
            centrum kształcenia zawodowego.
        </div>
    </div>
</div>
</div>',\Mpdf\HTMLParserMode::HTML_BODY);
$mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);
//$mpdf->OutputHttpDownload('download.pdf');
ob_clean();
$mpdf->SetTitle('Podgląd');
$mpdf->Output("Zaświadczenie_{$names_surname}_Kurs_{$course_code}.pdf", 'I');
}
elseif($_SERVER["REQUEST_METHOD"] == "POST") {
    return http_response_code(501);
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