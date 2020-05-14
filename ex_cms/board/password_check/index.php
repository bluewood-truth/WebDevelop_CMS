<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    if(isset($_GET["id"]) == false || isset($_GET["action"]) == false || isset($_GET["pid"]) == false){
        invalid_access();
    }

    $_SESSION["prev_page"] = "http://uraman.m-hosting.kr/ex_cms/board/?id=".$_GET["id"]."&pid=".$_GET["pid"];

    $process = "";
    switch($_GET["action"]){
        case "edit_cmt":
            if(!isset($_GET["cid"]))
                kick(11);
            $process = "http://uraman.m-hosting.kr/ex_cms/board/edit_comment/?id=".$_GET["id"]."&cid=".$_GET["cid"];
            break;
        case "delete_cmt":
            if(!isset($_GET["cid"]))
                kick(12);
            $process = "http://uraman.m-hosting.kr/ex_cms/board/_comment_delete_process.php/?id=".$_GET["id"]."&cid=".$_GET["cid"];
            break;
        case "edit_post":
            $process = "http://uraman.m-hosting.kr/ex_cms/board/write_post/?action=edit_post&id=".$_GET["id"]."&pid=".$_GET["pid"];
            break;
        case "delete_post":
            $process = "http://uraman.m-hosting.kr/ex_cms/board/_post_delete_process.php?id=".$_GET["id"]."&pid=".$_GET["pid"];
            break;
    }

    // SESSION체크하고 해당 작성자id와 일치하면 바로 프로세스로 넘김
    if(isset($_SESSION['login'])){
        // 댓글일 경우
        if(isset($_GET["cid"])){
            $sql = "SELECT author_id FROM CMS_comment_".$_GET["id"]." WHERE id=".$_GET["cid"];
            $result = sql_get_row(sql_query($sql))["author_id"];

            if(!is_null($result) && $result == $_SESSION["login"]){
                header("Location:".$process);
                exit;
            }
        }
        // 글일 경우
        if(isset($_GET["pid"])){
            $sql = "SELECT author_id FROM CMS_post_".$_GET["id"]." WHERE id=".$_GET["pid"];
            $result = sql_get_row(sql_query($sql))["author_id"];

            if(!is_null($result) && $result == $_SESSION["login"]){
                header("Location:".$process);
                exit;
            }
        }
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
    <div id="main-content">
        <div class="screen-width">
            <h2><a href="<?echo $board_link; ?>"><? echo $title; ?></a></h2>

            <div id="password-box">
                <form action="<? echo $process ?>" method="post">
                    <h3>비밀번호를 입력하세요.</h3>
                    <input id="pw" minlength=2 maxlength=16 required="required" type="password" name="password" value="">
                    <input id="btn-ok" type="submit" value="확인" <?if($_GET["action"] == "delete_post") echo 'onsubmit="return confirm('."'정말로 삭제하시겠습니까?'".')'?>>
                </form>
            </div>
        </div>
    </div>
    <?insert_parts("footer.html")?>
</body>
</html>
