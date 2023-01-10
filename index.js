window.addEventListener('load', function () {
console.log("loaded")
$('input').val("")
$('input:first').focus()
})


















































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