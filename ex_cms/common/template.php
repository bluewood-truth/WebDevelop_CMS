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
    <div id="main-content">

    </div>
    <? insert_parts("footer.html") ?>
</body>
</html>
