<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    // id가 있어야 함
    if(isset($_GET["id"]) == false){
        invalid_access();
    }

    // action 세션이 없으면 추가
    if(!isset($_SESSION["action"])){
        $_SESSION["action"] = "write_post";
    }

    // // 비회원인데 password가 없으면 kick
    // if(!isset($_SESSION["login"]) && !isset($_POST["password"]))
    //     kick(2);

    // $result = sql_query("SELECT * FROM CMS_comment_".$_GET["id"]." WHERE id=".$_GET["cid"]);
    // if(sql_get_num_rows($result) == 0){
    //     invalid_access("존재하지 않는 댓글입니다.", $_SESSION["prev_page"]);
    // }
    // $cmt = sql_get_row($result);
    //
    // // 로그인중이고 cmt author_id가 세션과 일치하지 않으면 kick
    // if(isset($_SESSION["login"])){
    //     if($_SESSION["login"] != $cmt["author_id"]){
    //         kick(3);
    //     }
    // }
    // // 로그인 중이 아니고 패스워드가 cmt guest_password와 일치하지 않으면 뒤로가기
    // // 일치하면 패스워드를 세션에 저장
    // else{
    //     if(sha1($_POST["password"]) != $cmt["guest_password"]){
    //         invalid_access("비밀번호가 일치하지 않습니다.",$_SESSION["prev_page"]);
    //     }
    //     else {
    //         $_SESSION["password"] = $_POST["password"];
    //     }
    // }

    $sql = "SELECT name_kor,category_list FROM CMS_board WHERE id='".$_GET["id"]."'";
    $result = sql_get_row(sql_query($sql));
    $title = $result['name_kor'];
    $board_link = "http://uraman.m-hosting.kr/ex_cms/board/?id=".$_GET["id"];

    // category_list가 null이 아니라면 select를 만든다
    function category_list(){
        global $result;
        if(!is_null($result['category_list'])){
            $category = explode("|",$result['category_list']);
            echo '<select class="" required="required" name="category">';
            echo '<option value="">분류</option>';
            for($i = 0; $i < count($category); $i++){
                echo '<option value="'.$category[$i].'">'.$category[$i].'</option>';
            }
            echo "</select>";
        }
    }
 ?>

<!DOCTYPE html>
<html>
<head>
 	<meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="http://uraman.m-hosting.kr/ex_cms/common/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="/_SmartEditor2/js/service/HuskyEZCreator.js" charset="utf-8"></script>
</head>
<body>
    <?insert_parts("header.php")?>
    <div class="screen-width">
        <h2><a href="<? echo $board_link; ?>"><? echo $title; ?></a></h2>
        <div id="post-write-box">
            <form action="http://uraman.m-hosting.kr/ex_cms/board/write_post/_process.php?id=<?echo $_GET["id"];?>" method="post">
                <p class="post-write-header">
                    <?
                    if(isset($_SESSION["login"])==false){
                        echo '
                        <input type="text" name="post-write-name" minlength=2 maxlength=8 placeholder="닉네임" value="" required="required">
                        <input type="password" name="post-write-pw" minlength=2 maxlength=16 placeholder="패스워드" value="" required="required">';
                    }
                    ?>
                </p>
                <p class="post-write-title">
                    <? category_list(); ?>
                    <input type="text" name="title" value="" placeholder="제목" required="required">
                </p>
                <textarea class="texteditor" name="ir1" id="ir1" rows="10" cols="100"></textarea>
                <div class="post-bottom-buttons">
                    <button id="cancel" type="button" class="btn-mini bg-gray">취소</button>
                    <button id="ok" type="submit" class="btn-mini bg-orange">등록</button>
                </div>
            </form>
        </div>
    </div>
</body>
<script>
    var oEditors = [];
    nhn.husky.EZCreator.createInIFrame({
        oAppRef: oEditors,
        elPlaceHolder: "ir1",
        sSkinURI: "/_SmartEditor2/SmartEditor2Skin.html",
        fCreator: "createSEditor2"
    });

    $("#post-write-box > form")[0].addEventListener("submit",function(event){
        oEditors.getById["ir1"].exec("UPDATE_CONTENTS_FIELD", []);
        var ir1 = $("#ir1")[0].value;

        if( ir1 == ""  || ir1 == null || ir1 == '&nbsp;' || ir1 == '<p>&nbsp;</p>' || ir1 == '<p><br></p>')  {
             alert("내용을 입력하세요.");
             oEditors.getById["ir1"].exec("FOCUS"); //포커싱
             event.preventDefault();
        }

        try {
            elClickedObj.submit();
        } catch(e) {}
    });

    $("#cancel")[0].addEventListener("click", function(){
        if(confirm("글 작성을 취소하시겠습니까?")){
            location.href=document.referrer;
        }
    })
</script>
</html>
