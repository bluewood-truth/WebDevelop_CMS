<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    if(!isset($_POST["group_name"]))
        kick(0);

    $name = filter($_POST["group_name"]);

    if(!strlen_check($name,1,7))
        kick(1);

    $result = sql_query("SELECT order_nav FROM CMS_board_group ORDER BY order_nav DESC");
    $max_num = sql_get_row($result)["order_nav"];
    $order = $max_num + 1;

    $sql = "INSERT INTO CMS_board_group (name_kor,order_nav)
            VALUES ('".$name."','".$order."')";
    sql_query($sql);

    header("location:/ex_cms/admin/?tab=boards");
 ?>
