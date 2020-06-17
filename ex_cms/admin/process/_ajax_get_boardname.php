<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    $id = $_POST["id"];

    $sql = "SELECT name_kor FROM CMS_board WHERE id='".$id."'";
    echo sql_get_row(sql_query($sql))["name_kor"];
 ?>
