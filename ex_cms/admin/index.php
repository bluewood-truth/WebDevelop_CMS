<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    if(access_check("admin") == false){
        invalid_access("잘못된 접근입니다.","http://uraman.m-hosting.kr/ex_cms");
    }

    if(!isset($_GET["tab"]))
    {
        invalid_access();
    }

    function tab_selected($tab){
        if($_GET["tab"] == $tab){
            echo "class='selected'";
        }else if($tab == "boards"){
            if($_GET["tab"] == "board_order" || $_GET["tab"] == "board_edit")
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
                <li <?tab_selected("members")?>><a href="http://uraman.m-hosting.kr/ex_cms/admin/?tab=members">회원 관리</a></li>
                <li <?tab_selected("ban_members")?>><a href="http://uraman.m-hosting.kr/ex_cms/admin/?tab=ban_members">정지/탈퇴 회원</a></li>
                <li <?tab_selected("boards")?>><a href="http://uraman.m-hosting.kr/ex_cms/admin/?tab=boards">게시판 관리</a></li>
                <li <?tab_selected("main")?>><a href="http://uraman.m-hosting.kr/ex_cms/admin/?tab=main">메인화면 관리</a></li>
                <li <?tab_selected("agree")?>><a href="http://uraman.m-hosting.kr/ex_cms/admin/?tab=agree">이용약관</a></li>
            </ul>
            <div id="tab-body">
                <?include $_SERVER["DOCUMENT_ROOT"]."/ex_cms/admin/_".$_GET["tab"].".php";?>
            </div>
        </div>
    </div>
    <? insert_parts("footer.html") ?>
</body>
</html>
