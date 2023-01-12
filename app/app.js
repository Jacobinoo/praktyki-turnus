import { newCourseForm,renderCourseDetailsView } from "./functions.js"

const body = document.querySelector("body");
const addBtn = document.querySelector(".add-btn");
const mainUI_List = document.querySelector("#container")
const logoutBtn = document.querySelector(".logout-btn");
const selectedCourse = document.querySelector(".course");


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
  const response = await fetch('http://localhost:80/praktyki-turnus/server/api.php?logout=1', {
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
//Course Click >> Course Details
selectedCourse.addEventListener("click", function (e) {
  courseId = e.currentTarget.dataset.id
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