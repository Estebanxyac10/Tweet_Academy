<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twitter</title>
    <link rel="stylesheet" href="/code/css/style.css" type="text/css">
    <link rel="stylesheet" href="/code/css/home.css" type="text/css">
    <link rel="stylesheet" href="/code/css/header.css" type="text/css">
    <link rel="stylesheet" href="/code/css/register.css" type="text/css">
    <link rel="stylesheet" href="/code/css/login.css" type="text/css">
    <link rel="stylesheet" href="/code/css/newtweet.css" type="text/css">
    <link rel="stylesheet" href="/code/css/tweet.css" type="text/css">
    <link rel="stylesheet" href="/code/css/dm.css" type="text/css">
    <link rel="stylesheet" href="/code/css/timeline.css" type="text/css">
    <link rel="icon" href="asset/img/png/logo_250.png" type="image/png">
    <script src="https://code.iconify.design/iconify-icon/2.0.0/iconify-icon.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="/code/view/javascript/search.js"></script>
    <script src="/code/view/javascript/forms.js"></script>
    <script src="/code/view/javascript/follow.js"></script>
</head>

<body>
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION["user_data"])) {
        ?>
        <div id="log_or_register">
            <img src="asset/img/png/logo_100.png" alt="Logo Twitter" title="Logo Twitter">
            <h2>Log in or Register to access Twitter!</h2>
            <button id="user_login">Log in</button>
            <button id="user_register">Register</button>
        </div>
        <?php
        include_once "code/view/html/login.html";
        include_once "code/view/html/register.html";
    } else {
        include_once "code/view/html/header.html";
        include_once "code/view/html/home.html";
        include_once "code/tweet/tweet.html";
        include_once "code/dm/dm.html";
    }
    ?>
</body>

</html>