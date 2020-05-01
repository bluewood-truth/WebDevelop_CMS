<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    unset($_SESSION['login']);
    header('Location:'.$_SERVER['HTTP_REFERER']);
 ?>
