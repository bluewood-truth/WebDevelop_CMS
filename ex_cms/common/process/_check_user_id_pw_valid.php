<?php
include $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
sql_connect();

echo sql_login_check($_POST["id"],$_POST["pw"]) ? 'true' : 'false';
 ?>
