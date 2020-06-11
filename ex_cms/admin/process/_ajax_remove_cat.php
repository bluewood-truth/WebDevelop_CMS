<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    $bid = filter($_POST["bid"]);
    $cat = filter($_POST["cat"]);

    $sql = "SELECT category_list FROM CMS_board WHERE id='".$bid."'";
    $result = sql_query($sql);
    if(sql_get_num_rows($result) == 0)
        kick(0);

    $cat_list = explode('|',sql_get_row($result)["category_list"]);
    $new_list = "";

    for($i=0; $i < count($cat_list); $i++){
        if($cat_list[$i] == $cat)
            continue;

        $new_list = $new_list.$cat_list[$i]."|";
    }

    $new_list = mb_substr($new_list, 0, -1);

    $sql = "UPDATE CMS_board SET category_list='".$new_list."' WHERE id='".$bid."'";
    sql_query($sql);
 ?>
