<?php
    ini_set('display_errors', 1);
    $conn = "";

    // =============================================================
    // SQL 관련
    // =============================================================
    function sql_connect(){
        global $conn;
        session_start();
        $conn = mysqli_connect("localhost",'uraman','!Q2w3e4r');
        mysqli_select_db($conn, 'uraman');
        mysqli_query($conn,"SET names UTF8");
    }

    function sql_query($query){
        global $conn;
        return mysqli_query($conn,$query);
    }

    function sql_get_row($table){
        return mysqli_fetch_assoc($table);
    }

    function sql_duplicate_check($value, $table, $col, $only_not_deleted = "false"){
        $sql = "SELECT ".$col." FROM ".$table." WHERE ".$col." = '".$value."'";
        if($only_not_deleted == "true"){
            $sql = $sql." AND deleted = 0";
        }
        //echo $sql;

        $result = sql_query($sql);
        $result = sql_get_row($result);

        return empty(!$result);
    }




    function filter($text, $is_text=false){
        global $conn;

        $text = htmlspecialchars($text);
        if($is_text ){
            $text = str_replace("\n","<br>",$text);
        }
        $text = mysqli_real_escape_string($conn,$text);

        return $text;
    }


    function invalid_access($msg="잘못된 접근입니다."){
        echo '<script>';
        echo 'alert("'.$msg.'");';
        echo 'location.href=document.referrer;';
        echo '</script>';
    }


    function insert_parts($parts_name, $is_php=false){
        include $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/parts/".$parts_name.($is_php?".php":".html");
    }
?>
