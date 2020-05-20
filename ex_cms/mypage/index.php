<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    if(!isset($_SESSION["login"])){
        invalid_access("로그인해주세요.","http://uraman.m-hosting.kr/ex_cms/login/");
    }

    if(!isset($_GET["tab"]))
    {
        invalid_access();
    }

    function tab_selected($tab){
        if($_GET["tab"] == $tab){
            echo "class='selected'";
        }
    }
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
        <div class="screen-width" style="width:800px;">
            <h2>마이페이지</h2>
            <ul id="tab-menu">
                <li <?tab_selected("info")?>><a href="http://uraman.m-hosting.kr/ex_cms/mypage/?tab=info">기본정보</a></li>
                <li <?tab_selected("posts")?>><a href="http://uraman.m-hosting.kr/ex_cms/mypage/?tab=posts">작성 글 목록</a></li>
                <li <?tab_selected("cmts")?>><a href="http://uraman.m-hosting.kr/ex_cms/mypage/?tab=cmts">작성 댓글 목록</a></li>
            </ul>
            <div id="tab-body">
                <?include $_SERVER["DOCUMENT_ROOT"]."/ex_cms/mypage/_".$_GET["tab"].".php";?>
            </div>
        </div>
    </div>
    <? insert_parts("footer.html") ?>
</body>
</html>
