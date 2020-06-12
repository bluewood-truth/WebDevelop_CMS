<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    if(!isset($_SESSION["login"])){
        kick(0);
    }
    if(!isset($_POST["nickname"])){
        kick(1);
    }

    $nickname = filter($_POST["nickname"]);
    if(!strlen_check($nickname,2,8)){
        kick(2);
    }

    $sql = "UPDATE CMS_userinfo SET nickname = '".$nickname."' WHERE id=".$_SESSION["login"];
    sql_query($sql);

    echo'
    <script>
        alert("닉네임이 변경되었습니다.");
        location.href="http://uraman.m-hosting.kr/ex_cms/mypage/?tab=info";
    </script>
    '
 ?>
