const replaceLastNChars = function(str, replace, num) {
  return str.slice(0, -num) + Array(num + 1).join(replace);
};

export function newCourseForm() {
  return `<div class="new-course-form-header">Tworzenie nowego turnusu<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
</svg></div>
<div class="new-course-form-content">
  <h3 class="new-course-form-content-section-title">Informacje podstawowe</h3>
  <h4 id="name">Podaj kod zawodu, aby pokazać nazwę</h4>
  <div class="new-course-form-content-info-wrapper">
    <input type="phone" maxlength="6" placeholder="Kod zawodu" class="new-course-form-content-input" id="code">
    <input type="text" placeholder="Klasa (np. IV)" class="new-course-form-content-input" id="class">
  </div>
  <h3 class="new-course-form-content-section-title">Termin realizacji</h3>
  <div class="new-course-form-content-date-wrapper">
    <label for="range-from">Data rozpoczęcia</label>
    <input id="range-from" type="date" class="new-course-form-content-date-input">
    <label for="range-to">Data zakończenia</label>
    <input id="range-to" type="date" class="new-course-form-content-date-input">
  </div>
  <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
  <span style="display: none;" id="error-label"></span>
  <div class="add-course-btn">Dodaj turnus</div>
</div>`
}
export function newParticipantForm() {
  return `<div class="new-participant-form-header">Dodawanie ucznia do turnusu<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
</svg></div>
<div class="new-participant-form-content">
  <h3 class="new-participant-form-content-section-title">Dane osobowe</h3>
  <div class="new-participant-form-content-info-wrapper">
    <input type="text" placeholder="Imię i nazwisko" class="new-participant-form-content-input" id="fullname">
    <input type="text" placeholder="Miejsce urodzenia (np. Kielce)" class="new-participant-form-content-input" id="birthplace">
    <input type="phone" placeholder="PESEL" class="new-participant-form-content-input" id="pesel" maxlength="11">
    <input type="text" placeholder="Adres e-mail ucznia" class="new-participant-form-content-input" id="birthplace">
    <textarea placeholder="Adres zamieszkania: np. ul. Mickiewicza 10/3. 28-200 Staszów" id="address" rows="3"></textarea>
    <label for="birthdate">Data urodzenia</label>
    <input id="birthdate" type="date" class="new-participant-form-content-date-input">
  </div>
  <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
  <span style="display: none;" id="error-label"></span>
  <div class="add-participant-btn">Dodaj ucznia</div>
</div>`
}
export function renderCourseDetailsView(courseData) {
  return `
    <div class="course-details-header">
    <div class="course-details-header-wrapper">
      ${courseData.name}
      <div class="course-details-header-squares">
      <span>Kod: <b>${courseData.code}</b></span>
      <span>Termin: <b>${courseData.startDate} - ${courseData.endDate}</b></span>
      <span>Klasa: <b>${courseData.class}</b></span>
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
    ${participantsTable(courseData.participants)}
    </div>
    </div>`;

    function participantsTable(participantsData){
      return `
      <table>
  <thead>
    <tr>
      <th>Edycja</th>
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
    ${participantRows(participantsData)}
  </tbody>
</table>`
function participantRows(participantsData) {
  let html = ``
  participantsData.forEach(participant => {
    console.log("participant",participant)
    html = html + `
    <tr>
    <td style="cursor: pointer;" data-id="${participant.uuid}" class="edit-participant-click"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-person-gear participant-edit-icon edit-participant-click" data-id="${participant.uuid}" viewBox="0 0 16 16">
    <path class="edit-participant-click" data-id="${participant.uuid}" d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm.256 7a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Zm3.63-4.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382l.045-.148ZM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z"/>
  </svg></td>
  <td>${participant.full_name}</td>
  <td>${(new Date(participant.birth_date)).toLocaleDateString('pl-pl')}</td>
  <td>${participant.birth_place}</td>
  <td>${replaceLastNChars(participant.pesel, "*", 6)}</td>
  <td>${participant.school_name}</td>
  <td>${participant.address}</td>
  <td>${participant.email}</td>
  <td><a class="download-certificate-btn">Pokaż</a></td>
</tr>
    `
  });
  return html
}
    }

}
export function newGradeForm(isConductGrade = false){
  if(isConductGrade == false) {
    return `<div class="new-grade-form-header">Dodawanie oceny<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
  </svg></div>
  <div class="new-grade-form-content">
    <div class="new-grade-form-wrapper">
      <input type="text" placeholder="Nazwa zajęć" class="new-grade-form-content-input" id="subject-name">
      <input type="text" placeholder="Wymiar godzin zajęć" class="new-grade-form-content-input" id="range-hours">
      <select id="grade" class="new-grade-form-select">
        <option value="6">celujący</option>
        <option value="5">bardzo dobry</option>
        <option value="4">dobry</option>
        <option value="3">dostateczny</option>
        <option value="2">dopuszczający</option>
        <option value="1">niedostateczny</option>
      </select>
    </div>
    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
    <span style="display: none;" id="error-label"></span>
    <div class="new-grade-form-add-grade-btn">Dodaj ocenę</div>
  </div>`
  } else {
    return `<div class="new-grade-form-header">Ocena zachowania<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
  </svg></div>
  <div class="new-conduct-grade-form-content">
    <div class="new-conduct-grade-form-wrapper">
      <select id="conduct-grade" class="new-grade-form-select">
        <option value="6">wzorowe</option>
        <option value="5">bardzo dobre</option>
        <option value="4">dobre</option>
        <option value="3">poprawne</option>
        <option value="2">nieodpowiednie</option>
        <option value="1">naganne</option>
      </select>
    </div>
    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
    <span style="display: none;" id="error-label"></span>
    <div class="new-grade-form-add-grade-btn">Zapisz ocenę</div>
  </div>`
  }
}
export function renderParticipantDetailsView(data) {
  return `
    <div class="participant-details-header">
    <div class="participant-details-header-wrapper">
    Uczeń: ${data.full_name}
      <div class="participant-details-header-squares">
      <span>PESEL: <b>${data.pesel}</b></span>
      <span>Data urodzenia: <b>${(new Date(data.birth_date)).toLocaleDateString('pl-pl')}</b></span>
      <span>Miejsce urodzenia: <b>${data.birth_place}</b></span>
      <span>Szkoła: <b>${data.school_name}</b></span>
      <span>Adres zamieszkania: <b>${data.address}</b></span>
      <span>E-mail: <b>${data.email}</b></span>
      </div>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
  </svg>
    </div>
    <div class="participant-details-content">
    <div class="participant-details-content-section">
    <h2>Oceny</h2>
    <div class="participant-details-add-grade-btn">Dodaj ocenę</div>
    ${gradesTable()}
    <div class="conduct-grade-box">Ocena z zachowania:<div class="conduct-grade"></div>
    <div class="conduct-grade-btn"></div>
    </div>
    </div>
    </div>`;

    function gradesTable(){
      return `
<table>
  <thead>
    <tr>
      <th>Edycja</th>
      <th>Nazwa zajęć</th>
      <th>Wymiar godzin zajęć</th>
      <th>Ocena</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td style="cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-pen participant-grade-edit-icon" viewBox="0 0 16 16">
      <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/>
    </svg></td>
      <td>Kosmetyka </td>
      <td>15 godzin</td>
      <td>5</td>
    </tr>
  </tbody>
</table>`
    }
}