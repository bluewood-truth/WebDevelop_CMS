<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    if(!isset($_POST["group_name"]))
        kick(0);

    if(!isset($_POST["group_id"]))
        kick(1);

    $name = filter($_POST["group_name"]);
    $id = $_POST["group_id"];

    $sql = "UPDATE CMS_board_group SET name_kor='".$name."' WHERE id=".$id;
    sql_query($sql);

    invalid_access("게시판그룹명이 수정되었습니다.", "/ex_cms/admin/?tab=boards");
 ?>
