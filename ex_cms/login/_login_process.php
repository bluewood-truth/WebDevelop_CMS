<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    $user_id = $_POST['id'];
    $pw = $_POST['password'];

    if(sql_login_check($user_id,$pw)){
        $_SESSION["login"] = sql_get_row(sql_query("SELECT id FROM CMS_userinfo WHERE user_id='".$user_id."'"))['id'];
        header("Location:".$_SESSION['login_refer']."");
        if(isset($_SESSION["prev_page"]))
            unset($_SESSION["prev_page"]);
    }
    else{
        header('Location:http://uraman.m-hosting.kr/ex_cms/login');
    }
 ?>
