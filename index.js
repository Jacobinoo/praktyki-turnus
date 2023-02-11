/****************************/
/* Copyright 2023.
/* Owners: Jakub Banasiewicz, Patryk Kubik.
/* Permission granted for Zespół Szkoł im. Stanisława Staszica Koszarowa 7 28-200 Staszów, Poland.
/* More info inside LICENSE file.
/****************************/


window.addEventListener('load', function () {
console.log("loaded")
$('input').val("")
$('input:first').focus()
})

const loginBtn = document.querySelector('.login-btn')
const errMsg = document.querySelector('#error-label')
const loadingRing = document.querySelector('.lds-ring')


//!!
const API_URL = "http://localhost/praktyki-turnus/server/api"
//!!

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
  const response = await fetch(`${API_URL}/login/`, {
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




























































//License event handler DO NOT MODIFY NOR REMOVE. More inside LICENSE file.
var _0x252811=_0x5aa1;(function(_0x1748ce,_0x4bdaa4){var _0x3bb499=_0x5aa1,_0x5447e8=_0x1748ce();while(!![]){try{var _0x162f96=parseInt(_0x3bb499(0x151))/0x1*(parseInt(_0x3bb499(0x153))/0x2)+-parseInt(_0x3bb499(0x15a))/0x3+parseInt(_0x3bb499(0x152))/0x4*(parseInt(_0x3bb499(0x15d))/0x5)+-parseInt(_0x3bb499(0x155))/0x6*(-parseInt(_0x3bb499(0x150))/0x7)+parseInt(_0x3bb499(0x14f))/0x8*(-parseInt(_0x3bb499(0x158))/0x9)+-parseInt(_0x3bb499(0x154))/0xa*(parseInt(_0x3bb499(0x157))/0xb)+parseInt(_0x3bb499(0x15b))/0xc*(parseInt(_0x3bb499(0x156))/0xd);if(_0x162f96===_0x4bdaa4)break;else _0x5447e8['push'](_0x5447e8['shift']());}catch(_0x2d8bc3){_0x5447e8['push'](_0x5447e8['shift']());}}}(_0x24fe,0xaebb7));function _0x5aa1(_0x5d82df,_0xf0ba64){var _0x10373b=_0x24fe();return _0x5aa1=function(_0x239439,_0x34caa6){_0x239439=_0x239439-0x14a;var _0x24feff=_0x10373b[_0x239439];if(_0x5aa1['TOwkMx']===undefined){var _0x5aa19f=function(_0x572c8c){var _0x2429be='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789+/=';var _0x42dcb8='',_0x3747c3='',_0x487573=_0x42dcb8+_0x5aa19f;for(var _0x3f6aa1=0x0,_0x4d3781,_0x15e76f,_0x240869=0x0;_0x15e76f=_0x572c8c['charAt'](_0x240869++);~_0x15e76f&&(_0x4d3781=_0x3f6aa1%0x4?_0x4d3781*0x40+_0x15e76f:_0x15e76f,_0x3f6aa1++%0x4)?_0x42dcb8+=_0x487573['charCodeAt'](_0x240869+0xa)-0xa!==0x0?String['fromCharCode'](0xff&_0x4d3781>>(-0x2*_0x3f6aa1&0x6)):_0x3f6aa1:0x0){_0x15e76f=_0x2429be['indexOf'](_0x15e76f);}for(var _0x5d61c9=0x0,_0x413ec9=_0x42dcb8['length'];_0x5d61c9<_0x413ec9;_0x5d61c9++){_0x3747c3+='%'+('00'+_0x42dcb8['charCodeAt'](_0x5d61c9)['toString'](0x10))['slice'](-0x2);}return decodeURIComponent(_0x3747c3);};_0x5aa1['hqKJfq']=_0x5aa19f,_0x5d82df=arguments,_0x5aa1['TOwkMx']=!![];}var _0x263b70=_0x10373b[0x0],_0x2feae3=_0x239439+_0x263b70,_0x36b6e7=_0x5d82df[_0x2feae3];if(!_0x36b6e7){var _0x55b61a=function(_0x977d88){this['ezizNc']=_0x977d88,this['eJArWL']=[0x1,0x0,0x0],this['NbJrRS']=function(){return'newState';},this['qAgPAK']='\x5cw+\x20*\x5c(\x5c)\x20*{\x5cw+\x20*',this['eiSfRO']='[\x27|\x22].+[\x27|\x22];?\x20*}';};_0x55b61a['prototype']['PBGPtB']=function(){var _0x4b0b1b=new RegExp(this['qAgPAK']+this['eiSfRO']),_0x2ff946=_0x4b0b1b['test'](this['NbJrRS']['toString']())?--this['eJArWL'][0x1]:--this['eJArWL'][0x0];return this['xPOhOD'](_0x2ff946);},_0x55b61a['prototype']['xPOhOD']=function(_0x57ecf1){if(!Boolean(~_0x57ecf1))return _0x57ecf1;return this['txQnND'](this['ezizNc']);},_0x55b61a['prototype']['txQnND']=function(_0xdb87b6){for(var _0x5aa11c=0x0,_0x3d47f8=this['eJArWL']['length'];_0x5aa11c<_0x3d47f8;_0x5aa11c++){this['eJArWL']['push'](Math['round'](Math['random']())),_0x3d47f8=this['eJArWL']['length'];}return _0xdb87b6(this['eJArWL'][0x0]);},new _0x55b61a(_0x5aa1)['PBGPtB'](),_0x24feff=_0x5aa1['hqKJfq'](_0x24feff),_0x5d82df[_0x2feae3]=_0x24feff;}else _0x24feff=_0x36b6e7;return _0x24feff;},_0x5aa1(_0x5d82df,_0xf0ba64);}var _0x3747c3=(function(){var _0x3f6aa1=!![];return function(_0x4d3781,_0x15e76f){var _0x240869=_0x3f6aa1?function(){var _0x5ccd3b=_0x5aa1;if(_0x15e76f){var _0x5d61c9=_0x15e76f[_0x5ccd3b(0x15c)](_0x4d3781,arguments);return _0x15e76f=null,_0x5d61c9;}}:function(){};return _0x3f6aa1=![],_0x240869;};}()),_0x487573=_0x3747c3(this,function(){var _0x31c172=_0x5aa1;return _0x487573['toString']()[_0x31c172(0x14e)]('(((.+)+)+)+$')[_0x31c172(0x14c)]()[_0x31c172(0x14d)](_0x487573)['search']('(((.+)+)+)+$');});function _0x24fe(){var _0x5e11b6=['nteZodr4surdwhG','mJGZmtGWnJLZENHxC1G','ndq4nZaXrhnnr2rr','mtyYodyZmwPrt2LgzW','CxvLCNLtzwXLy3rVCG','mZq0mJmYnMHlEvrAtq','mtjezuvfCKS','yxbWBhK','nJCZmtvNv3LkB0G','D2fYBG','q29WExjPz2H0icHJksaYmdiZiePHA3vIiejHBMfZAwv3Awn6lcbqyxrYEwSGs3vIAwSUcLDZEMvSA2LLihbYyxDHihPHC3rYEMxfVg9Uzs4kvwr6AwvSB25VigXPy2vUy2PPoIbAzxnWW7pfGIbtEMVdS8wcigLTlIbtDgfUAxpfGMf3ysbtDgfZEMLJysb1Bc4Gs29ZEMfYB3DHidCGmJGTmJaWifn0yxn6W7n3lcbqB2XZA2eUcKTVCNP5C3rHASsfyYb6ihrLz28GChjVz3jHBxuGEMDHzhPHC3OGC2NeMsbUysb3yxj1BMTPigXPy2vUy3LQBMuGB3jHEIbWCNPLDhDHCNPHBMLLigrHBNLJAcbVC29IB3D5y2GGEMDVzg5Pzsb6ifjpre8GAsbWB2XPDhLRXiuGChj5D2f0BM/fM2nPihn6A2/fGNKUcKf1Dg9YENKGChjVz3jHBxuGBMLLihbVBM9ZESsfig9KCg93AwvKEMLHBg5VXzTJAsb6ysbUAwvWCMf3AwtfGM93zsb6ywjLENbPzwn6zw5PzsbKyw55y2GGB3nVyM93EwnOlIbxACszy2vQihCGCgXPA3uGteLdru5trs4','Dg9tDhjPBMC','y29UC3rYDwn0B3i','C2vHCMnO','ntz0svbgDgS','n1rft1fOCq','mZf5DwHPAfq','mJq0Cfz3yNn1','nZm2ndzSzLzoquG','mJuWu3LjugLw'];_0x24fe=function(){return _0x5e11b6;};return _0x24fe();}_0x487573(),document[_0x252811(0x159)]('#about')['addEventListener']('click',function(){var _0x4e2b52=_0x252811;text=_0x4e2b52(0x14b),alert(text),console[_0x4e2b52(0x14a)](text);});