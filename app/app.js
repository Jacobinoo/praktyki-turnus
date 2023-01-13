import { newCourseForm,renderCourseDetailsView } from "./functions.js"
const body = document.querySelector("body");
const courseList = document.querySelector("#content");
const addBtn = document.querySelector(".add-btn");
const mainUI_List = document.querySelector("#container")
const logoutBtn = document.querySelector(".logout-btn");


let courseId = null




//Curtain
const curtain = document.querySelector(".curtain");
//New Course Form
addBtn.addEventListener("click", function () {
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
      curtain.style.display = "none";
      form.remove();
    });
}

//Logout Btn
logoutBtn.addEventListener("click", function () {
logout()
async function logout() {
  const response = await fetch('http://localhost/praktyki-turnus/server/api/logout', {
    method: 'GET',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    }
  })
  response.json().then(data => {
    if(response.status !== 200) throw new Error(`${data.error}`)
    console.log(data)
    if(data.status == "success") {
      return window.location.href = "../index.php"
    } else {
      return false
    }
  }).catch((err) => {
    console.error(err)
  })
}
});


// Course List
(async function fetchAllCourses() {
  const response = await fetch('http://localhost/praktyki-turnus/server/api/course', {
    method: 'GET',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    }
  })
  response.json().then(data => {
    if(response.status !== 200) throw new Error(`${data.error}`)
    console.log(data.courses)
    if(data.status == "success") {
      (data.courses).forEach(course => {
        const courseComponent = document.createElement("div");
        const startDateParts = course.start_date.split("-")
        const  startDate = new Date(startDateParts[0], startDateParts[1] - 1, startDateParts[2].substr(0,2)).toLocaleDateString('pl-pl', { year:"numeric", month:"numeric", day:"numeric"})
        const endDateParts = course.end_date.split("-")
        const endDate = new Date(endDateParts[0], endDateParts[1] - 1, endDateParts[2].substr(0,2)).toLocaleDateString('pl-pl', { year:"numeric", month:"numeric", day:"numeric"})
        courseComponent.innerHTML = `
        <span class="course-name course-click-listener" data-id='${course.uuid}'>${course.name}</span
        >
        <div class="squares course-click-listener" data-id='${course.uuid}'>
          <div class="code course-click-listener" data-id='${course.uuid}'>${course.code}</div>
          <div class="date course-click-listener" data-id='${course.uuid}'>${startDate} - ${endDate}</div>
          <div class="status course-click-listener" data-id='${course.uuid}'><span class="status-text course-click-listener" data-id='${course.uuid}'>${course.status ? "Aktywny":"Nieaktywny"}</span></div>
        </div>`;
      courseComponent.className = "course course-click-listener";
      courseComponent.dataset.id = course.uuid;
      courseList.appendChild(courseComponent);
      });
    } else {
      throw new Error(`${data.error}`)
    }
  }).catch((err) => {
    console.error(err)
  })
})()

















//Course Click >> Course Details
document.querySelector('#content').addEventListener("click", function (e) {
  if(!e.target.classList.contains('course-click-listener')) {
    return
  }
  courseId = e.target.dataset.id
  console.log(`Redirecting to Details View of Course: ${courseId}`);
  UI_redirectDetailsView()
});

function UI_redirectDetailsView() {
  const UI = document.createElement("div");
  UI.innerHTML = renderCourseDetailsView(courseId);
  UI.className = "course-details";
  mainUI_List.style.display = "none"
  body.appendChild(UI);
  UI
  .querySelector(".bi-x-circle-fill")
  .addEventListener("click", function () {
    UI.style.display = "none"
    mainUI_List.style.display = "block";
  });
}