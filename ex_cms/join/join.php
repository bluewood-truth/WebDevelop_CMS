<?php
    include $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();
 ?>

<!DOCTYPE html>
<html>
<head>
 	<meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/ex_cms/common/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="/ex_cms/common/common.js"></script>
</head>
<body>
    <? insert_parts("big_logo_header.html") ?>
    <div id="join-container" class="white-shadow-box">
        <?php
            if(isset($_GET['page']) == false){
                invalid_access();
            }
            if($_GET['page'] == "agreement"){
                include '1_agreement.php';
            } else if ($_GET['page'] == "form"){
                include '2_form.php';
            } else if ($_GET['page'] == "joined"){
                include '3_joined.php';
            } else{
                invalid_access();
            }
         ?>
    </div>
</body>
</html>
