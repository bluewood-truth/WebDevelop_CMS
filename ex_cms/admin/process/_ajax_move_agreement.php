<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    if(!isset($_POST["id"]) || !isset($_POST["direction"]))
        kick(0);


    $id = $_POST["id"];
    $direction = $_POST["direction"];

    $sql = "SELECT CMS_agreement.order FROM CMS_agreement WHERE id=".$id;
    $this_order = sql_get_row(sql_query($sql))["order"];

    if($direction == "up"){
        $sql="SELECT id,CMS_agreement.order FROM CMS_agreement WHERE CMS_agreement.order<".$this_order." ORDER BY CMS_agreement.order DESC LIMIT 0,1";
        $result = sql_query($sql);
        if(sql_get_num_rows($result)==0){
            echo "uperror";
            exit;
        }
        $row = sql_get_row($result);
        $target_id = $row["id"];
        $target_order = $row["order"];
    }
    else if($direction == "down"){
        $sql="SELECT id,CMS_agreement.order FROM CMS_agreement WHERE CMS_agreement.order>".$this_order." ORDER BY CMS_agreement.order ASC LIMIT 0,1";
        $result = sql_query($sql);
        if(sql_get_num_rows($result)==0){
            echo "downerror";
            exit;
        }
        $row = sql_get_row($result);
        $target_id = $row["id"];
        $target_order = $row["order"];
    }
    else{
        kick(2);
    }

    $sql = "UPDATE CMS_agreement SET CMS_agreement.order=".$target_order." WHERE id=".$id;
    sql_query($sql);
    $sql = "UPDATE CMS_agreement SET CMS_agreement.order=".$this_order." WHERE id=".$target_id;
    sql_query($sql);
    echo $sql;
 ?>
