<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    header('Content-Type: text/html; charset=utf-8');
    ini_set('display_errors', 1);
    $conn = "";

    // =============================================================
    // SQL 관련
    // =============================================================
    function sql_connect(){
        global $conn;
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

    function sql_get_num_rows($table){
        return mysqli_num_rows($table);
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

    function sql_login_check($id, $pw){
        $pw = sha1($pw);

        $sql = "SELECT user_id,password FROM CMS_userinfo WHERE user_id = '".$id."' AND password = '".$pw."'";
        $result = sql_query($sql);
        $result = sql_get_row($result);

        // 해당 id, pw에 해당하는 계정이 존재하면 true, 둘중 하나라도 틀리면 false
        return empty(!$result);
    }


    function referer(){
        if(isset($_SERVER['HTTP_REFERER']))
            header("Location:".$_SERVER['HTTP_REFERER']);
    }

    function filter($text, $is_text=false){
        global $conn;

        $text = htmlspecialchars($text);
        if($is_text ){
            $text = str_replace("\n","<br>",$text);
        }
        $text = mysqli_real_escape_string($conn,$text);
        $text = preg_replace('/\s+/', ' ',$text);
        return $text;
    }




    function invalid_access($msg="잘못된 접근입니다.", $link="referer"){
        echo '<script>';
        echo 'alert("'.$msg.'");';

        if($link == "referer"){
            echo 'location.href=document.referrer;';
        }
        else{
            echo 'location.href="'.$link.'";';
        }
        echo '</script>';

        if(isset($_SESSION["prev_page"])){
            unset($_SESSION["prev_page"]);
        }
        exit;
    }

    function kick($code=null){
        if(is_null($code)){
            header("Location:https://www.google.com/");
        }
        else{
            echo '<script>';
            echo 'alert("'.$code.'");';
            echo 'location.href="https://www.google.com/";';
            echo '</script>';
        }
        exit;
    }

    function insert_parts($parts_name){
        include $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/parts/".$parts_name;
    }

    function member_icon(){
        return "<span class='member-icon'>회원</span>";
    }

    function get_login_nickname($icon = true){
        if(isset($_SESSION['login'])){
            $id = $_SESSION['login'];
            $sql = "SELECT nickname FROM CMS_userinfo WHERE id='".$id."'";
            $result = sql_get_row(sql_query($sql));
            $result = $result['nickname'];
            if($icon)
                $result = $result.member_icon();
            return $result;
        }
        else{
            return NULL;
        }
    }

    function strlen_check($text, $minlength, $maxlength){
        if(mb_strlen($text) < $minlength || mb_strlen($text) > $maxlength){
            return false;
        }
        return true;
    }
?>
