<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    $sql = "SELECT * FROM CMS_board WHERE display_on_main IS NOT NULL ORDER BY display_on_main";
    $result = sql_query($sql);
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
            <div id="board-display-container">
                <?
                    while($row = sql_get_row($result)){
                        $sql = "SELECT * FROM CMS_post_".$row["id"]." ORDER BY id DESC LIMIT 0,6";
                        $posts = sql_query($sql);
                        echo '
                        <div class="board-display">
                            <h3><a href="http://uraman.m-hosting.kr/ex_cms/board/?id='.$row["id"].'">'.$row["name_kor"].'</a></h3>
                            <ul>';
                            while($post = sql_get_row($posts)){
                                echo '<li><a href="http://uraman.m-hosting.kr/ex_cms/board/?id='.$row["id"].'&pid='.$post["id"].'">'.$post["title"].'</a></li>';
                            }
                        echo '
                            </ul>
                        </div>';
                    }
                ?>
            </div>
        </div>
    </div>
    <? insert_parts("footer.html") ?>
</body>
<script>
    for(var i = 0; i < $(".board-display li").length; i++){
        $(".board-display li a")[i].innerText = text_cutting($(".board-display li")[i].innerText, 24);
    }
</script>
</html>
