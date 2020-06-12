<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    $bid = $_POST["bid"];
    $name = $_POST["name_kor"];
    $on_menu = $_POST["display_on_menu"]=="true"?true:false;
    $cat_list = explode('|',sql_get_row($result)["category_list"]);
    $access = $_POST["access"];
    $group = $_POST["group"];

    $new_list = "";
    for($i=0; $i < count($cat_list); $i++){
        if($cat_list[$i] == $cat)
            continue;

        $new_list = $new_list.$cat_list[$i]."|";
    }
    $new_list = mb_substr($new_list, 0, -1);

    $order_sub = null;
    if($on_menu){
        $result = sql_query("SELECT order_sub FROM CMS_board WHERE group_id='".$group."' ORDER BY order_sub DESC");
        $max_num = sql_get_row($result)["order_sub"];
        $order_sub = $max_num + 1;
    }

    $sql = "UPDATE CMS_board SET name_kor='".$name."', "
?>
