<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();
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
        <h2>게시판 제목</h2>
        <?
            if(isset($_GET["post"]))
                include $_SERVER["DOCUMENT_ROOT"]."/ex_cms/board/view.php";
        ?>

        <? include $_SERVER["DOCUMENT_ROOT"]."/ex_cms/board/list.php"; ?>
    </div>
</body>
</html>
