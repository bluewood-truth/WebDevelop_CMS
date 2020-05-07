<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    if(isset($_GET["id"]) == false){
        invalid_access();
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
        <?
            if(isset($_GET["post"]))
                include $_SERVER["DOCUMENT_ROOT"]."/ex_cms/board/view.php";

            include $_SERVER["DOCUMENT_ROOT"]."/ex_cms/board/list.php";
        ?>
    </div>
</body>
</html>
