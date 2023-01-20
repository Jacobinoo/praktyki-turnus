import { newCourseForm, newParticipantForm, renderCourseDetailsView, renderParticipantDetailsView, newGradeForm } from "./functions.js";

//Kwalifikacje - symbole cyfrowe JSON
import kwalifikacje from './kwalifikacje.js'
const body = document.querySelector("body");
const courseList = document.querySelector("#content");
const addBtn = document.querySelector(".add-btn");
const mainUI_List = document.querySelector("#container");
const logoutBtn = document.querySelector(".logout-btn");

let courseId = null;
let participantId = null;
let participantsData = null;
let isCourseDetailsOpen = false
let isAddCourseFormOpen = false
let isAddParticipantFormOpen = false
let isParticipantDetailsOpen = false
let isAddGradeFormOpen = false
let isEditGradeFormOpen = false

//Curtain
const curtain = document.querySelector(".curtain");
//New Course Form
addBtn.addEventListener("click", function () {
  if(isAddCourseFormOpen == true) return;
  isAddCourseFormOpen = true
  UI_showNewCourseForm();
});
function UI_showNewCourseForm() {
  curtain.style.display = "flex";
  const form = document.createElement("div");
  form.innerHTML = newCourseForm();
  form.className = "new-course-form";
  curtain.appendChild(form);
  form
    .querySelector(".bi-x-circle-fill")
    .addEventListener("click", function () {
      isAddCourseFormOpen = false
      curtain.style.display = "none";
      form.remove();
    });
    document.querySelector('#code').addEventListener('keyup', function () {
      let input = document.querySelector('#code').value
      try {
        if(kwalifikacje[input].name) {
          console.log(kwalifikacje[input].name)
          document.querySelector('#name').textContent = `${kwalifikacje[input].name}`
          document.querySelector('#name').className = "course-name-set"
        }
      } catch (e) {
        console.log('Nie znaleziono zawodu o podanym kodzie')
        document.querySelector('#name').className = ""
        document.querySelector('#name').textContent = "Podaj kod zawodu, aby pokazać nazwę"
      }
    })
}


function UI_showNewParticipantForm() {
  curtain.style.display = "flex";
  const form = document.createElement("div");
  form.innerHTML = newParticipantForm();
  form.className = "new-participant-form";
  curtain.appendChild(form);
  form
    .querySelector(".bi-x-circle-fill")
    .addEventListener("click", function () {
      isAddParticipantFormOpen = false
      curtain.style.display = "none";
      form.remove();
    });
}

function UI_showNewGradeForm(isConductGrade = false){
  curtain.style.display = "flex";
  const form = document.createElement("div");
  form.innerHTML = newGradeForm(isConductGrade);
  form.className = "new-grade-form";
  curtain.appendChild(form);
  form
    .querySelector(".bi-x-circle-fill")
    .addEventListener("click", function () {
      isAddGradeFormOpen = false
      curtain.style.display = "none";
      form.remove();
    });
}

//Logout Btn
logoutBtn.addEventListener("click", function () {
  logout();
  async function logout() {
    const response = await fetch(
      "http://localhost/praktyki-turnus/server/api/logout/",
      {
        method: "GET",
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
      }
    );
    response
      .json()
      .then((data) => {
        if (response.status !== 200) throw new Error(`${data.error}`);
        console.log(data);
        if (data.status == "success") {
          return (window.location.href = "../index.php");
        } else {
          return false;
        }
      })
      .catch((err) => {
        console.error(err);
      });
  }
});

//Add new course
document.querySelector(".curtain").addEventListener("click", function (e) {
  if (!e.target.classList.contains("add-course-btn")) {
    return;
  }
  if(document.querySelector('#name').className != "course-name-set") {
    const errMsg = document.querySelector('#error-label')
    errMsg.textContent = "Nieprawidłowy kod zawodu"
    errMsg.style.display = "block"
    return;
  }
  createCourse(curtain.querySelector('#code').value, curtain.querySelector('#name').value, curtain.querySelector('#class').value, curtain.querySelector('#range-from').value, curtain.querySelector('#range-to').value).then(_ => {
  })
});
async function createCourse(code, name, _class, startDate, endDate) {
  const addBtn = document.querySelector('.add-btn')
  const errMsg = document.querySelector('#error-label')
  const loadingRing = document.querySelector('.lds-ring')
  if(errMsg.textContent !== "") errMsg.textContent = ""
  addBtn.style.display = "none"
  loadingRing.style.display = "inline-block"
  const response = await fetch(
    "http://localhost/praktyki-turnus/server/api/course/",
    {
      method: "POST",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: `{"course_code": "${code}","course_name": "${name}","course_class": "${_class}","start_date": "${startDate}","end_date": "${endDate}"}`,
    }
  );
  response
    .json()
    .then((data) => {
      if (response.status !== 200) throw `${data.error}`;
      if (data.status == "success") {
        createCourseComponent(true, data.uuid, _class, code, (new Date(startDate)).toLocaleDateString('pl-pl'), (new Date(startDate)).toLocaleDateString('pl-pl'))
        curtain.style.display = "none"
        curtain.replaceChildren()
      } else {
        throw `${data.error}`;
      }
    })
    .catch((err) => {
      console.error(err);
      addBtn.style.display = "flex"
      loadingRing.style.display = "none"
      errMsg.textContent = `${err}`
      errMsg.style.display = "block"
    });
}

// Course List
(async function fetchAllCourses() {
  const response = await fetch(
    "http://localhost/praktyki-turnus/server/api/course/",
    {
      method: "GET",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
    }
  );
  response
    .json()
    .then((data) => {
      if (response.status !== 200) throw `${data.error}`;
      console.log(data.courses);
      if (data.status == "success") {
        document.querySelector('.lds-ring').style.display = "none"
        if(data.courses.length == 0) {
          document.querySelector('#empty-label').style.display = "block"
        }
        data.courses.forEach((course) => {
          const startDateParts = course.start_date.split("-");
          const startDate = new Date(
            startDateParts[0],
            startDateParts[1] - 1,
            startDateParts[2].substr(0, 2)
          ).toLocaleDateString("pl-pl", {
            year: "numeric",
            month: "numeric",
            day: "numeric",
          });
          const endDateParts = course.end_date.split("-");
          const endDate = new Date(
            endDateParts[0],
            endDateParts[1] - 1,
            endDateParts[2].substr(0, 2)
          ).toLocaleDateString("pl-pl", {
            year: "numeric",
            month: "numeric",
            day: "numeric",
          });
          createCourseComponent(false, course.uuid, course.class, course.code, startDate, endDate, course.status)
        });
      } else {
        throw `${data.error}`;
      }
    })
    .catch((err) => {
      console.error(err);
      document.querySelector('.lds-ring').style.display = "none"
      document.querySelector('#error-label').textContent = `${err}`
      document.querySelector('#error-label').style.display = "block"
    });
})();


function createCourseComponent(onTheTop = false, uuid, _class, code, startDate, endDate, status) {
  const name = kwalifikacje[code].name
  const courseComponent = document.createElement("div");
  courseComponent.innerHTML = `
  <span class="course-name course-click-listener" data-id='${uuid}'>${name}</span>
  <div class="squares course-click-listener" data-id='${uuid}'>
    <div class="code course-click-listener" data-id='${uuid}'>${code}</div>
    <div class="date course-click-listener" data-id='${uuid}'>${startDate} - ${endDate}</div>
    <div class="date course-click-listener" data-id='${uuid}'>Klasa ${_class}</div>
    <div class="status course-click-listener" data-id='${uuid}'><span class="status-text course-click-listener" data-id='${uuid}'>${
    status ? "Aktywny" : "Nieaktywny"
  }</span></div>
  </div>`;
  courseComponent.className = "course course-click-listener";
  courseComponent.dataset.id = uuid;
  if(onTheTop == true) {
    courseList.prepend(courseComponent);
  } else {
    courseList.append(courseComponent);
  }
}

//Course Click >> Course Details
document.querySelector("#content").addEventListener("click", function (e) {
  if (!e.target.classList.contains("course-click-listener")) {
    return;
  }
  if(isCourseDetailsOpen == true) return;
  isCourseDetailsOpen = true
  courseId = e.target.dataset.id;
  console.log(`Redirecting to Details View of Course: ${courseId}`);
  UI_redirectDetailsView()
});

//New participant button click
document.querySelector("body").addEventListener("click", function (e) {
  if (!e.target.classList.contains("course-details-content-section-btn")) {
    return;
  }
  if(isAddParticipantFormOpen == true) return;
  isAddParticipantFormOpen = true
  UI_showNewParticipantForm()
});

//Edit participant button click
document.querySelector("body").addEventListener("click", function (e) {
  if (!e.target.classList.contains("edit-participant-click")) {
    return;
  }
  if(isParticipantDetailsOpen == true) return;
  isParticipantDetailsOpen = true
  participantId = e.target.dataset.id
  UI_redirectParticipantView()
});

//New grade button click
document.querySelector("body").addEventListener("click", function (e) {
  if(e.target.classList.contains("participant-details-add-grade-btn")) {
    if(isAddGradeFormOpen == true) return;
    isAddGradeFormOpen = true
    UI_showNewGradeForm()
  } else if (e.target.classList.contains("conduct-grade-btn")) {
    if(isAddGradeFormOpen == true) return;
    isAddGradeFormOpen = true
    UI_showNewGradeForm(true)
  }
});

async function UI_redirectParticipantView() {
  console.log(`redirecting to participant ${participantId}`)
  const UI = document.createElement("div");
  UI.className = "participant-details";
  UI.innerHTML = renderParticipantDetailsView(participantsData.find(x => x.uuid == participantId))
  mainUI_List.style.display = "none";
  body.appendChild(UI);
  document.querySelector(".conduct-grade").innerText == "" ? document.querySelector(".conduct-grade-btn").innerText = "Dodaj" : document.querySelector(".conduct-grade-btn").innerText = "Zmień"
  document.querySelector('.course-details').style.display = "none"
  UI.querySelector(".bi-x-circle-fill").addEventListener("click", function () {
    isParticipantDetailsOpen = false
    UI.remove()
    document.querySelector('.course-details').style.display = "block"
  });
}



async function UI_redirectDetailsView() {
  let courseData = {}
  const UI = document.createElement("div");
  UI.className = "course-details";
  const response = await fetch(
    `http://localhost/praktyki-turnus/server/api/course/?id=${courseId}`,
    {
      method: "GET",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
    }
  );
  response
    .json()
    .then((data) => {
      if (response.status !== 200) throw new Error(`${data.error}`);
      if (data.status == "success") {
          const startDateParts = data.course.start_date.split("-");
          const startDate = new Date(
            startDateParts[0],
            startDateParts[1] - 1,
            startDateParts[2].substr(0, 2)
          ).toLocaleDateString("pl-pl", {
            year: "numeric",
            month: "numeric",
            day: "numeric",
          });
          const endDateParts = data.course.end_date.split("-");
          const endDate = new Date(
            endDateParts[0],
            endDateParts[1] - 1,
            endDateParts[2].substr(0, 2)
          ).toLocaleDateString("pl-pl", {
            year: "numeric",
            month: "numeric",
            day: "numeric",
          });
          courseData = {
            uuid:data.course.uuid,
            name:kwalifikacje[data.course.code].name,
            code:data.course.code,
            class:data.course.class,
            startDate:startDate,
            endDate:endDate,
            participants: data.participants
          }
          UI.innerHTML = renderCourseDetailsView(courseData);
          mainUI_List.style.display = "none";
          participantsData = courseData.participants
          body.appendChild(UI);
          console.log(participantsData)
          UI.querySelector(".bi-x-circle-fill").addEventListener("click", function () {
            isCourseDetailsOpen = false
            UI.remove()
            mainUI_List.style.display = "block";
          });
      } else {
        throw new Error(`${data.error}`);
      }
    })
    .catch((err) => {
      console.error(err);
    });
}
