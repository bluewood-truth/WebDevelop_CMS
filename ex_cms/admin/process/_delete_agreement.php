<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    if(!isset($_POST["id"]))
        kick(0);

    $id = $_POST["id"];

    $sql = "DELETE FROM CMS_agreement WHERE id=".$id;
    sql_query($sql);
    header("location:/ex_cms/admin/?tab=agree");
?>
