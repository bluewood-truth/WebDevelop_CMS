<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    if(!isset($_POST["group_id"]))
        kick(0);

    if(!access_check("admin")){
        kick(1);
    }

    $id = $_POST["group_id"];
    $sql = "SELECT id FROM CMS_board WHERE group_id=".$id;
    $result = sql_query($sql);
    if(sql_get_num_rows($result) > 0){
        invalid_access("게시판이 존재하는 게시판그룹은 삭제할 수 없습니다.");
    }

    $sql="DELETE FROM CMS_board_group WHERE id=".$id;
    sql_query($sql);
    invalid_access("게시판그룹이 삭제되었습니다.", "/ex_cms/admin/?tab=boards");
 ?>
