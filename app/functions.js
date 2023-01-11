export function newCourseForm() {
  return `<div class="new-course-form-header">Tworzenie nowego turnusu<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
</svg></div>
<div class="new-course-form-content">
  <h3 class="new-course-form-content-section-title">Informacje podstawowe</h3>
  <div class="new-course-form-content-info-wrapper">
    <input type="phone" maxlength="6" placeholder="Kod zawodu" class="new-course-form-content-input">
    <input type="text" placeholder="Nazwa zawodu" class="new-course-form-content-input">
    <input type="text" placeholder="Klasa (np. IV)" class="new-course-form-content-input">
  </div>
  <h3 class="new-course-form-content-section-title">Termin realizacji</h3>
  <div class="new-course-form-content-date-wrapper">
    <label for="range-from">Data rozpoczęcia</label>
    <input id="range-from" type="date" class="new-course-form-content-date-input">
    <label for="range-to">Data zakończenia</label>
    <input id="range-to" type="date" class="new-course-form-content-date-input">
  </div>
  <div class="add-course-btn">Dodaj turnus</div>
</div>`
}
export function renderCourseDetailsView(courseId) {
  return `
    <div class="course-details-header">
    <div class="course-details-header-wrapper">
      Monter zabudowy i robót wykończeniowych w budownictwie
      <div class="course-details-header-squares">
      <span>Kod: <b>123456</b></span>
      <span>Termin: <b>02.11.2022 - 30.11.2022</b></span>
      <span>Status: <b>Aktywny</b></span>
      </div>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
  </svg>
    </div>
    <div class="course-details-content">
    <div class="course-details-content-section">
    <h2>Uczniowie</h2>
    <div class="course-details-content-section-btn">Dodaj ucznia</div>
    ${participantsTable()}
    </div>
    <div class="course-details-content-section">
    <h2>Oceny</h2>
    <p class="grayed-caption">Brak danych do wyświetlenia</p>
    ${gradesTable()}
    </div>
    </div>`;

    function participantsTable(){
      return `
      <table>
  <thead>
    <tr>
      <th>Edycja</th>
      <th>Lp.</th>
      <th>Imię i nazwisko</th>
      <th>Data urodzenia</th>
      <th>Miejsce urodzenia</th>
      <th>PESEL</th>
      <th>Szkoła</th>
      <th>Adres zamieszkania</th>
      <th>Adres e-mail</th>
      <th>Zaświadczenie</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td style="cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-person-gear participant-edit-icon" viewBox="0 0 16 16">
      <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm.256 7a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Zm3.63-4.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382l.045-.148ZM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z"/>
    </svg></td>
      <td>1.</td>
      <td>Jan Kowalski</td>
      <td>01.11.2002</td>
      <td>Kraków</td>
      <td>12345678901</td>
      <td>ZS Staszów</td>
      <td>ul.Mieckiewicza 1, 28-200 Staszów</td>
      <td>test@gmail.com</td>
      <td><a href="https://www.mbludzm.pl/sw-jan-pawel-ii" class="download-certificate-btn" target="_blank">Pokaż</a></td>
    </tr>
  </tbody>
</table>`
    }


    function newParticipantForm() {
      return `<div class="new-participant-form">
        <h3>Informacje o uczniu</h3>
        <input type="text" id="name-surname" placeholder="Imię i nazwisko">
        <input type="date" id="birth-date">
        <input type="text" if="birth-place" placeholder="Miejsce urodzenia">
        <input type="phone" maxlength="11" id="PESEL" placeholder="PESEL">
        <input type="text" id="school" placeholder="Szkoła">
        <input type="text" id="current-house-place" placeholder="Adres zamieszkania">
        <input type="email" id="email" placeholder="Adres e-mail">
        <div class="new-participant-form-add-btn">Dodaj ucznia</div>
        </div>`
    }
    function gradesTable(){
      return `
      <table class="grades-table">
  <thead>
    <tr>
      <th>Edycja</th>
      <th>Lp.</th>
      <th>Imię i nazwisko</th>
      <th>Data urodzenia</th>
      <th>Miejsce urodzenia</th>
      <th>PESEL</th>
      <th>Szkoła</th>
      <th>Adres zamieszkania</th>
      <th>Adres e-mail</th>
      <th>Zaświadczenie</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td style="cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-person-gear participant-edit-icon" viewBox="0 0 16 16">
      <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm.256 7a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Zm3.63-4.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382l.045-.148ZM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z"/>
    </svg></td>
      <td>1.</td>
      <td>Jan Kowalski</td>
      <td>01.11.2002</td>
      <td>Kraków</td>
      <td>12345678901</td>
      <td>ZS Staszów</td>
      <td>ul.Mieckiewicza 1, 28-200 Staszów</td>
      <td>test@gmail.com</td>
      <td><a href="https://www.mbludzm.pl/sw-jan-pawel-ii" class="download-certificate-btn" target="_blank">Pokaż</a></td>
    </tr>
  </tbody>
</table>`
    }
}