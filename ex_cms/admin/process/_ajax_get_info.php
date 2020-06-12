<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    $type = $_POST["type"];
    $id = $_POST["id"];

    $sql = "SELECT * FROM ";
    if($type == "board")
        $sql = $sql."CMS_board ";
    else if($type == "group")
        $sql = $sql."CMS_board_group ";
    $sql = $sql."WHERE id='".$id."'";

    $result = sql_query($sql);

    $data = array();

    while($row = sql_get_row($result)){
        $data[] = $row;
    }

    array_converter($data);
 ?>
