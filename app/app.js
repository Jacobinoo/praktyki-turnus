import { Template } from "./templates.js";
const templates = new Template()
let addBtn = document.querySelector(".add-btn")
const curtain = document.querySelector(".curtain")
UI_showNewCourseForm()
addBtn.addEventListener("click", function () {
  UI_showNewCourseForm()
});

function UI_showNewCourseForm() {
  curtain.style.display = "flex"
  const form = document.createElement("div")
  form.innerHTML = templates.newCourseForm
  form.className = "new-course-form"
  curtain.appendChild(form)

  form.querySelector('svg').addEventListener('click', function () {
    curtain.style.display = "none"
    form.remove()
  })


}
