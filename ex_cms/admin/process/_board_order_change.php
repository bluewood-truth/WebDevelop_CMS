<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    if(!isset($_POST["group_order"]))
        kick(0);
    if(!isset($_POST["board_order"]))
        kick(1);

    $prev_group = -1;
    $order=0;
    $arr = $_POST["board_order"];
    for($i = 0; $i < count($arr); $i++)
    {
        $tmp = explode(':',$arr[$i]);
        if($tmp[0] != $prev_group)
            $order = 0;

        $sql = "UPDATE CMS_board SET order_sub=".$order." WHERE id='".$tmp[1]."'";
        sql_query($sql);

        $prev_group = $tmp[0];
        $order += 1;
    }

    $arr = $_POST["group_order"];
    for($i = 0; $i < count($arr); $i++)
    {
        $sql = "UPDATE CMS_board_group SET order_nav=".$i." WHERE id=".$arr[$i];
        sql_query($sql);
    }

    header("location:/ex_cms/admin/?tab=boards");
    // invalid_access("변경이 완료되었습니다.", "/ex_cms/admin/?tab=boards");
 ?>
