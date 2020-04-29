<?php
include $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
sql_connect();

echo sql_duplicate_check($_POST["value"],$_POST["table"],$_POST["col"],$_POST["only_not_deleted"]) ? 'true' : 'false';

 ?>
