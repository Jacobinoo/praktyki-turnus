<?php
require_once __DIR__ . '/vendor/autoload.php';
header('Access-Control-Allow-Origin: http://127.0.0.1');
header('Access-Control-Allow-Headers: Content-Type');
$mpdf = new \Mpdf\Mpdf();
$stylesheet = file_get_contents('arkusz_ocen.css');
$mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);
$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML('
<div class="container">
<div class="place-date">Staszów dnia 02.12.2022</div>
<h4>ZESTAWIENIE WYNIKÓW KLASYFIKACJI</h4>
<div class="range-date">Turnus od 02-11-2022 do 30-12-2022</div>
<div class="turnus-name">Monter zabudowy i robót wykończeniowych w budownictwie [712905] KL III</div>
    <table>
        <thead>
        <tr>
            <th rowspan="2" class="LP">Lp.</th>
            <th rowspan="2" width="100pt">Imię nazwisko </th>
            <th rowspan="2" width="80pt">Szkoła kierująca na turnus</th>
            <th colspan="6">Wynik klasyfikacji z przedmiotów</th>
            <th>Zachowanie</th>
        </tr>
        <tr>
            <td>język obcy zawodowy</td>
            <td>Technologia robót wykończeniowych</td>
            <td>Technologia robót wykończeniowych</td>
            <td>Technologia robót wykończeniowych</td>
            <td>Technologia robót wykończeniowych</td>
            <td>Technologia robót wykończeniowych</td>
            <td>wzorowe</td>
        </tr>
        </thead>
    <tbody>
    <tr>
        <td>1.</td>
        <td>Frelian Wiktor Mateusz</td>
        <td>ZS Staszów</td>
        <td>5</td>
        <td>5</td>
        <td>4</td>
        <td>3</td>
        <td>3</td>
        <td>2</td>
        <td>wzorowe</td>
    </tr>
    </tbody>
    </table>
</div>





',\Mpdf\HTMLParserMode::HTML_BODY);
$mpdf->Output();