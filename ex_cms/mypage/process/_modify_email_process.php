<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    if(!isset($_SESSION["login"])){
        kick(0);
    }
    if(!isset($_POST["email"])){
        kick(1);
    }

    $check_email = "/^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i";
    $email = filter($_POST["email"]);
    if(!preg_match($check_email,$email)){
        kick(2);
    }

    $sql = "UPDATE CMS_userinfo SET email = '".$email."' WHERE id=".$_SESSION["login"];
    sql_query($sql);

    echo'
    <script>
        alert("이메일이 변경되었습니다.");
        location.href="http://uraman.m-hosting.kr/ex_cms/mypage/?tab=info";
    </script>
    '
 ?>
