<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    if(isset($_GET["id"]) == false || isset($_GET["cid"]) == false){
        invalid_access();
    }

    // prev_page가 없으면 kick (패스워드 체크 페이지를 넘어욌으면 있어야 함)
    if(!isset($_SESSION["prev_page"]))
        kick(1);

    // 비회원인데 password가 없으면 kick
    if(!isset($_SESSION["login"]) && !isset($_POST["password"]))
        kick(2);

    $result = sql_query("SELECT * FROM CMS_comment_".$_GET["id"]." WHERE id=".$_GET["cid"]);
    if(sql_get_num_rows($result) == 0){
        invalid_access("존재하지 않는 댓글입니다.", $_SESSION["prev_page"]);
    }
    $cmt = sql_get_row($result);

    // 로그인중이고 회원댓글일때 cmt author_id가 세션과 일치하지 않으면 kick
    if(isset($_SESSION["login"]) && !is_null($cmt["author_id"])){
        if($_SESSION["login"] != $cmt["author_id"]){
            kick(3);
        }
    }
    // 로그인 중이 아니고 패스워드가 cmt guest_password와 일치하지 않으면 뒤로가기
    // 일치하면 패스워드를 세션에 저장
    else{
        if(sha1($_POST["password"]) != $cmt["guest_password"]){
            invalid_access("비밀번호가 일치하지 않습니다.",$_SESSION["prev_page"]);
        }
        else {
            $_SESSION["password"] = $_POST["password"];
        }
    }

    $cmt_nickname = $cmt["guest_name"];
    if(is_null($cmt_nickname)){
        $cmt_nickname = $cmt["author_nickname"].member_icon();
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
    <div class="main-content">
        <div class="screen-width">
            <h2><a href="<?echo $board_link; ?>"><? echo $title; ?></a></h2>

            <div id="comment-edit-box">
                <form action="_process.php?id=<?echo $_GET["id"]?>&cid=<?echo $_GET["cid"]?>" method="post">
                    <div class="comment-write-box">
                        <div class="comment-write-head">
                            <div class="cmt-name long"><?echo $cmt_nickname; ?></div>
                        </div>
                        <div class="comment-write-textarea">
                            <textarea maxlength=512 name="cmt-write-content" rows="3" cols="80" required="required"><?echo str_replace("<br>","\n",$cmt["content"]);?></textarea>
                        </div>
                        <div class="comment-write-btn">
                            <input type="submit" name="" value="등록">
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <?insert_parts("footer.html")?>
</body>
</html>
