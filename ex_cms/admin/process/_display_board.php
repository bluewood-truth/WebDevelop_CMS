<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    if(!isset($_POST["display"]))
        kick(0);

    $arr = $_POST["display"];

    // 일단 현재 순서를 초기화
    $sql = "UPDATE CMS_board SET display_on_main=NULL";
    sql_query($sql);

    // 이후 입력받은 순서대로 업데이트
    for($i = 0; $i < count($arr); $i++){
        $sql = "UPDATE CMS_board SET display_on_main='".$i."' WHERE id='".$arr[$i]."'";
        sql_query($sql);
    }

    header("location:/ex_cms/admin/?tab=main");
 ?>
