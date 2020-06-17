<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    if(!isset($_POST["board_id"]))
        kick(0);

    if(!access_check("admin")){
        kick(1);
    }

    $id = $_POST["board_id"];

    $sql = "DELETE FROM CMS_post_check WHERE board_id='".$id."'";
    sql_query($sql);
    $sql = "DELETE FROM CMS_comment_check WHERE board_id='".$id."'";
    sql_query($sql);
    $sql = "DROP TABLE CMS_comment_".$id."'";
    sql_query($sql);
    $sql = "DROP TABLE CMS_post_".$id."'";
    sql_query($sql);
    $sql="DELETE FROM CMS_board WHERE id='".$id."'";
    sql_query($sql);

    invalid_access("게시판이 삭제되었습니다.", "/ex_cms/admin/?tab=boards");
 ?>
