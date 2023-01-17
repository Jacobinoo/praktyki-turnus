window.addEventListener('load', function () {
console.log("loaded")
$('input').val("")
$('input:first').focus()
})

const loginBtn = document.querySelector('.login-btn')
const errMsg = document.querySelector('#error-label')
const loadingRing = document.querySelector('.lds-ring')

document.querySelector('.login-btn').addEventListener('click', function () {
  let code = ""
  document.querySelectorAll('.code-input').forEach(input => {
    code += input.value
  });
  logIn(code)
})

async function logIn(code) {
  if(errMsg.textContent !== "") errMsg.textContent = ""
  loginBtn.style.display = "none"
  loadingRing.style.display = "inline-block"
  const response = await fetch('http://localhost/praktyki-turnus/server/api/login/', {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    },
    body: `{"login_code": "${code}"}`
  })
  response.json().then(data => {
    if(response.status !== 200) throw `${data.error}`
    console.log(data)
    window.location.href = "./app/panel.php"
  }).catch((err) => {
    console.error(err)
    loginBtn.style.display = "flex"
    loadingRing.style.display = "none"
    errMsg.textContent = `${err}`
    errMsg.style.display = "block"
  })
}















































// Input V Code Mechanics
var vcode = (function () {
  var $inputs = $("#input-wrapper").find("input");
  $inputs.on("keyup", processInput);

  function processInput(e) {
    var x = e.charCode || e.keyCode;
    if ((x == 8 || x == 46) && this.value.length == 0) {
      var indexNum = $inputs.index(this);
      if (indexNum != 0) {
        $inputs.eq($inputs.index(this) - 1).focus();
      }
    }

    if (ignoreChar(e)) return false;
    else if (this.value.length == this.maxLength) {
      $(this).next("input").focus();
    }
  }
  function ignoreChar(e) {
    var x = e.charCode || e.keyCode;
    if (x == 37 || x == 38 || x == 39 || x == 40) return true;
    else return false;
  }
})();
$("input")
  .focus(function () {
    $(this).selectRange(2); // set cursor position
  })
  .click(function () {
    $(this).selectRange(2); // set cursor position
  });

$.fn.selectRange = function (start, end) {
  if (end === undefined) {
    end = start;
  }
  return this.each(function () {
    if ("selectionStart" in this) {
      this.selectionStart = start;
      this.selectionEnd = end;
    } else if (this.setSelectionRange) {
      this.setSelectionRange(start, end);
    } else if (this.createTextRange) {
      var range = this.createTextRange();
      range.collapse(true);
      range.moveEnd("character", end);
      range.moveStart("character", start);
      range.select();
    }
  });
};