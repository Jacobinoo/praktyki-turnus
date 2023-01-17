<?php
require_once __DIR__ . '/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf();
$stylesheet = file_get_contents('style2.css');
$html = file_get_contents('html2.html');


$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML('<div class="text">Oceny uzyskane z przedmiotów zawodowych teoretycznych objętych programem naucznia realizowanym na turnusie dokształcania teoretycznego młodocianych pracowników:</div>
<table>
    <tr>
        <th>Lp.</th>
        <th>Nazwa zajęć</th>
        <th>Wymiar godzin zajęć</th>
        <th>Ocena<sup style="font-size: 6pt;">5)</sup></th>
    </tr>
    <tr>
        <td>1</td>
        <td>Jezyk angielski to bardzo popularny jezyk, prawdopodobnie najpopularniejszy jezyk swiata</td>
        <td>15h</td>
        <td>5</td>
    </tr>
</table>
<div style="margin-top: 10pt;">
<div class="grade-text">Ocena zachowania<sup style="font-size: 6pt;">6)</sup></div>
    <div class="grade-dots">'.$grade.'</div>
</div>
<div class="footer">
    <div class="line"></div>
    <div class="footer-text">5) Skala ocen: celujący, bardzo dobry, dostateczny, dopuszczający, niedostateczny.<br>
    6) Skala ocen: wzorowe, bradzo dobre, dobre, poprawne, nieodpowiednie, naganne.
    </div>
</div>',\Mpdf\HTMLParserMode::HTML_BODY);
$mpdf->Output();