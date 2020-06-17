<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    $bid = $_POST["bid"];
    $name = filter($_POST["name_kor"]);
    $on_menu = $_POST["display_on_menu"]=="true"?true:false;
    $cat_list = isset($_POST["cat"])?$_POST["cat"]:null;
    $access = $_POST["access"];
    $group = $_POST["group"];

    $new_list = "NULL";
    if(!is_null($cat_list)){
        $new_list = "";
        for($i=0; $i < count($cat_list); $i++){
            $new_list = $new_list.$cat_list[$i]."|";
        }
        $new_list = "'".mb_substr($new_list, 0, -1)."'";
    }

    $order_sub = null;
    if($on_menu){
        $prev_group = sql_get_row(sql_query("SELECT group_id FROM CMS_board WHERE id='".$bid."'"))["group_id"];

        $result = sql_query("SELECT order_sub FROM CMS_board WHERE id='".$bid."'");
        if(sql_get_num_rows($result) == 0 || $group != $prev_group){
            $result = sql_query("SELECT order_sub FROM CMS_board WHERE group_id='".$group."' ORDER BY order_sub DESC");
            $max_num = sql_get_row($result)["order_sub"];
            $order_sub = $max_num + 1;
        }
        else{
            $order_sub = sql_get_row($result)["order_sub"];
        }
    }
    $sql = "UPDATE CMS_board SET name_kor='".$name."', group_id='".$group."', order_sub='".$order_sub."', category_list=".$new_list." WHERE id='".$bid."'";

    sql_query($sql);
    header("location:/ex_cms/admin/?tab=boards");
?>
