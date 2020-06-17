<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    if(!isset($_POST["title"]) || !isset($_POST["content"]) || !isset($_POST["required"]) || !isset($_POST["id"]))
        kick(0);

    $title = filter($_POST["title"]);
    $content = filter($_POST["content"],true);
    $required = $_POST["required"];
    $id = $_POST["id"];

    if(!strlen_check($title,1,100) || mb_strlen($content) < 1){
        kick(1);
    }

    // 신규작성일 경우
    if($id == "null"){
        $sql = "SELECT CMS_agreement.order FROM CMS_agreement ORDER BY CMS_agreement.order DESC LIMIT 0,1";
        $order = sql_get_row(sql_query($sql))["order"] + 1;

        $sql = "INSERT INTO CMS_agreement (`agree_name`, `agree_content`, `is_required`, `order`)
                VALUES ('".$title."', '".$content."', '".$required."', '".$order."');";
    }
    else{
        $sql = "SELECT id FROM CMS_agreement WHERE id=".$id;
        $result = sql_query($sql);
        if(sql_get_num_rows($result) == 0)
            invalid_access("존재하지 않는 약관입니다.");

        $sql = "UPDATE CMS_agreement SET agree_name='".$title."', agree_content='".$content."', is_required=".$required."
                WHERE id=".$id;
    }
    sql_query($sql);
    header("location:/ex_cms/admin/?tab=agree");
 ?>
