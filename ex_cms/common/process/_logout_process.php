<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    unset($_SESSION['login']);

    if(isset($_SESSION["prev_page"])){
        header("Location:".$_SESSION["prev_page"]);
        unset($_SESSION["prev_page"]);
    }
    else if(isset($_SERVER['HTTP_REFERER']))
        header("Location:".$_SERVER['HTTP_REFERER']);
 ?>
