<?php
    include $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";

    $id = $_POST['id'];
    $pw = $_POST['pw'];

    if(sql_login_check($id,$pw)){
        $_SESSION['login'] = $_POST['id'];
        header('Location:http://uraman.m-hosting.kr/ex_cms/login');
    }
    else{
        header('Location:http://uraman.m-hosting.kr/ex_cms/');
    }
 ?>
