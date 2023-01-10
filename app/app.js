import { Template } from "./templates.js";
const templates = new Template();
const body = document.querySelector("body");
const addBtn = document.querySelector(".add-btn");
const mainUI_List = document.querySelector("#container")
const logoutBtn = document.querySelector(".logout-btn");
const selectedCourse = document.querySelector(".course");
//Curtain
const curtain = document.querySelector(".curtain");

//New Course Form
addBtn.addEventListener("click", function () {
  UI_showNewCourseForm();
});

function UI_showNewCourseForm() {
  curtain.style.display = "flex";
  const form = document.createElement("div");
  form.innerHTML = templates.newCourseForm;
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
  window.location.href = "/index.html"
});
UI_redirectDetailsView()
//Course Click >> Course Details
selectedCourse.addEventListener("click", function (e) {
  let courseId = e.currentTarget.dataset.id
  console.log(`Redirecting to Details View of Course: ${courseId}`);
  UI_redirectDetailsView()
});

function UI_redirectDetailsView() {
  const UI = document.createElement("div");
  UI.innerHTML = templates.courseDetailsView;
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