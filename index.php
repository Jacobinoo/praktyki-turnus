<?php
session_start();
if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] == true) {
  header('Location: ./app/panel.php');
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./index.css" type="text/css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script defer src="./index.js" type="text/javascript"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <style> @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap'); </style>
    <title>Turnus - zaloguj się</title>
</head>
<body>
    <div class="card">
        <div class="header">
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-mortarboard-fill" viewBox="0 0 16 16">
                <path d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917l-7.5-3.5Z"/>
                <path d="M4.176 9.032a.5.5 0 0 0-.656.327l-.5 1.7a.5.5 0 0 0 .294.605l4.5 1.8a.5.5 0 0 0 .372 0l4.5-1.8a.5.5 0 0 0 .294-.605l-.5-1.7a.5.5 0 0 0-.656-.327L8 10.466 4.176 9.032Z"/>
              </svg>
            <span>Turnusy</span>
        </div>
        <div class="form">
            <span for="input-wrapper">Podaj kod logowania</span>
            <div id="input-wrapper">
                <input type="phone" class="code-input" maxlength="1" />
                <input type="phone" class="code-input" maxlength="1" />
                <input type="phone" class="code-input" maxlength="1" />
                <input type="phone" class="code-input" maxlength="1" />
                <input type="phone" class="code-input" maxlength="1" />
                <input type="phone" class="code-input" maxlength="1" />
            </div>
            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
            <span style="display: none;" id="error-label"></span>
            <button class="login-btn">Zaloguj się</button>
        </div>
    </div>
</body>
</html>