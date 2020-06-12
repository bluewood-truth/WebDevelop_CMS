<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    $level = "";
    if(!isset($_SESSION["login"]))
        $level = "guest";
    else {
        $level = sql_get_row(sql_query("SELECT is_admin FROM CMS_userinfo WHERE id=".$_SESSION["login"]))["is_admin"];
    }
    echo $level;
 ?>
