<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    $id = $_POST['id'];
    $pw = $_POST['password'];

    if(sql_login_check($id,$pw)){
        $_SESSION["login"] = $id;
        header('Location:http://uraman.m-hosting.kr/ex_cms/');
    }
    else{
        header('Location:http://uraman.m-hosting.kr/ex_cms/login');
    }
 ?>
