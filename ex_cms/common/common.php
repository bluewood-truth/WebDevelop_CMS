<?php
    ini_set('display_errors', 1);
    $conn = "";

    // =============================================================
    // SQL 관련
    // =============================================================
    function sql_connect(){
        global $conn;
        session_start();
        $conn = mysqli_connect("localhost",'id','password');
        mysqli_select_db($conn, 'uraman');
    }

    function sql_query($query){
        global $conn;
        return mysqli_query($conn,$query);
    }





    function filter($conn, $text, $is_text=false){
        $text = htmlspecialchars($text);
        if($is_text ){
            $text = str_replace("\n","<br>",$text);
        }
        $text = mysqli_real_escape_string($conn,$text);

        return $text;
    }





    function insert_parts($parts_name, $is_php=false){
        include $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/parts/".$parts_name.($is_php?".php":".html");
    }
?>
