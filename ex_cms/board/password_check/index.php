<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    if(isset($_GET["id"]) == false || isset($_GET["action"]) == false){
        invalid_access();
    }

    $process = "";
    switch($_GET["action"]){
        case "edit_cmt":
            if(!isset($_GET["cid"]))
                kick();
            $process = "http://uraman.m-hosting.kr/ex_cms/board/edit_comment/?id=".$_GET["id"]."&cid=".$_GET["cid"];
            break;
        case "delete_cmt":
            if(!isset($_GET["cid"]))
                kick();
            $process = "http://uraman.m-hosting.kr/ex_cms/board/_delete_comment_process.php/?id=".$_GET["id"]."&cid=".$_GET["cid"];
            break;
    }

    // SESSION체크하고 로그인되어 있으면 바로 process로 패스함
    if(isset($_SESSION['login'])){

        // exit;
    }

    $sql = "SELECT name_kor FROM CMS_board WHERE id='".$_GET["id"]."'";
    $result = sql_get_row(sql_query($sql));
    $title = $result['name_kor'];
    $board_link = "http://uraman.m-hosting.kr/ex_cms/board/?id=".$_GET["id"];
 ?>

<!DOCTYPE html>
<html>
<head>
 	<meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="http://uraman.m-hosting.kr/ex_cms/common/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
    <?insert_parts("header.php")?>
    <div class="screen-width">
        <h2><a href="<?echo $board_link; ?>"><? echo $title; ?></a></h2>

        <div id="password-box">
            <form action="<? echo $process ?>" method="post">
            <h3>비밀번호를 입력하세요.</h3>
            <input id="pw" minlength=2 maxlength=16 required="required" type="password" name="" value="">
            <input id="btn-ok" type="submit" value="확인">
            </form>
        </div>

    </div>
</body>
</html>
