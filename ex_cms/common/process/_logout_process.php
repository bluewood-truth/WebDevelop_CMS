<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    unset($_SESSION['login']);
    if(isset($_SERVER['HTTP_REFERER']))
        header("Location:".$_SERVER['HTTP_REFERER']."");
 ?>
