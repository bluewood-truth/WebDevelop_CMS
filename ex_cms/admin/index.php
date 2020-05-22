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
            <h2>관리자 페이지</h2>
            <ul id="tab-menu">
                <li <?tab_selected("members")?>><a href="http://uraman.m-hosting.kr/ex_cms/admin/?tab=members">멤버 관리</a></li>
                <li <?tab_selected("boards")?>><a href="http://uraman.m-hosting.kr/ex_cms/admin/?tab=boards">게시판 관리</a></li>
            </ul>
            <div id="tab-body">
                <?include $_SERVER["DOCUMENT_ROOT"]."/ex_cms/admin/_".$_GET["tab"].".php";?>
            </div>
        </div>
    </div>
    <? insert_parts("footer.html") ?>
</body>
</html>
