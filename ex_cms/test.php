<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
sql_connect();

$sql = "SELECT * FROM CMS_userinfo WHERE id=4";
$result = sql_query($sql);
$row = sql_get_row($result);
echo $row["is_admin"];
 ?>
