<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();
if(!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] == "") {
    echo json_encode([
        'error' => 'Nie jesteś zalogowany(a)'
    ]);
    return http_response_code(403);
}
header('Access-Control-Allow-Origin: http://127.0.0.1');
header('Access-Control-Allow-Headers: Content-Type');
$mpdf = new \Mpdf\Mpdf();
$stylesheet = file_get_contents('zaswiadczenie.css');
$html = file_get_contents('zaswiadczenie.html');
$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);

define("CURRENT_DATE_PLACE", "Staszów, ".date("d.m.Y"));

$year = date('Y');
$year = $year[-2].$year[-1];


if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(!file_get_contents('php://input')) return http_response_code(400);
    $request = json_decode(file_get_contents('php://input'), true);

    if(!isset($request['names_surname']) || !isset($request['birth_date']) || !isset($request['birth_place']) || !isset($request['pesel']) || !isset($request['course_name']) || !isset($request['course_code']) || !isset($request['range']) || !isset($request['school'])) {
        echo json_encode([
            'error' => 'Nie podano wystarczająco danych'
        ]);
        return http_response_code(400);
    }
    if($request['names_surname'] == "" || $request['birth_date'] == "" || $request['birth_place'] == "" || $request['pesel'] == "" || $request['course_name'] == "" || $request['course_code'] == "" || $request['range'] == "" || $request['school'] == "") {
        echo json_encode([
            'error' => 'Dane nie mogą być puste'
        ]);
        return http_response_code(400);
    }

    $names_surname = $request['names_surname'];
    $birth_date = $request['birth_date'];
    $birth_place = $request['birth_place'];
    $pesel = $request['pesel'];
    $course_name = $request['course_name'];
    $course_code = $request['course_code'];
    $range = $request['range'];
    $school = $request['school'];
    $nr = $request['nr'];
}

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
$mpdf->OutputHttpDownload('download.pdf');
//$mpdf->Output();