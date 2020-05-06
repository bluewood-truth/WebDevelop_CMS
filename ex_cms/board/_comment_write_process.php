<?php
    function kick(){
        header("Location:https://www.google.com/");
        exit;
    }

    // 댓글 내용이 없으면 kick
    if(empty($_POST["cmt-write-content"])){
        kick();
    }

    // 

    // 로그인한 경우
    if(isset($_SESSION['login'])){
        $sql = "SELECT id,nickname FROM CMS_userinfo WHERE id='".$_SESSION['login']."'";
        $result = sql_query($sql);

        // 세션 id에 해당하는 계정이 없으면 kick
        if(sql_get_num_rows($result) == 0){
            kick();
        }

        $

        $sql = "INSERT INTO `` (nickname,password,content,datetime) values('".$nickname."','".$password."','".$content."',now())";
    }

    // 게스트일 경우
    else{

    }
 ?>
