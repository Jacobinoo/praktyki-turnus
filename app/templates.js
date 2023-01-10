export function Template() {
  this.newCourseForm = `<div class="new-course-form-header">Tworzenie nowego turnusu<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
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
  </div>`;
  this.courseDetailsView = `
  <div class="course-details-header">
  <div class="course-details-header-wrapper">
    {{%COURSE_TITLE%}}
    <div class="course-details-header-squares">
    <span>Kod zawodu: <b>{{%COURSE_CODE%}}</b></span>
    <span>Termin realizacji: <b>{{%COURSE_DATERANGE%}}</b></span>
    <span>Status: <b>{{%COURSE_STATUS%}}</b></span>
    </div>
  </div>
  <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
</svg>
  </div>
  <div class="course-details-content">
    <div class="new-participant-form">
    <h3>Informacje o uczestniku</h3>
    <input type="text" id="name-surname" placeholder="Imię i nazwisko">
    <input type="date" id="birth-date">
    <input type="text" if="birth-place" placeholder="Miejsce urodzenia">
    <input type="phone" maxlength="11" id="PESEL" placeholder="PESEL">
    <input type="text" id="school" placeholder="Szkoła">
    <input type="text" id="current-house-place" placeholder="Adres zamieszkania">
    <input type="email" id="email" placeholder="Adres e-mail">
    <div class="add-participant-btn">Dodaj uczestnika</div>
    </div>
  </div>
  `;
}
