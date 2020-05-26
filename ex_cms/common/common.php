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

    // 중복체크 (id, 닉네임 등)
    function sql_duplicate_check($value, $table, $col, $only_not_deleted = "false"){
        $sql = "SELECT ".$col." FROM ".$table." WHERE ".$col." = '".$value."'";
        if($only_not_deleted == "true"){
            $sql = $sql." AND deleted = 0";
        }

        $result = sql_query($sql);
        $result = sql_get_row($result);

        return empty(!$result);
    }

    function sql_login_check($id, $pw){
        $pw = sha1($pw);

        $sql = "SELECT id FROM CMS_userinfo WHERE user_id = '".$id."' AND password = '".$pw."'";
        $result = sql_query($sql);
        $result = sql_get_num_rows($result) != 0;

        // 해당 id, pw에 해당하는 계정이 존재하면 true, 둘중 하나라도 틀리면 false
        return $result;
    }

    function sql_table_exist_check($tablename){
        $sql = "SELECT id FROM CMS_board WHERE id='".$tablename."'";
        $result = sql_query($sql);
        if(sql_get_num_rows($result) > 0){
            return true;
        }
        else{
            return false;
        }
    }

    function sql_insert_key(){
        global $conn;
        return mysqli_insert_id($conn);
    }



// =============================================================
// 기타
// =============================================================
    function array_converter($arr){
        echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    }

    function referer(){
        if(isset($_SERVER['HTTP_REFERER']))
            header("Location:".$_SERVER['HTTP_REFERER']);
    }

    function referer_check($url){
        if(!isset($_SERVER['HTTP_REFERER']))
            kick("referer isn't set");
        else{
            if($_SERVER['HTTP_REFERER'] != $url){
                kick("referer isn't match");
            }
        }
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

    // 오류메세지를 띄우고 뒤로가기
    function invalid_access($msg="잘못된 접근입니다.", $link="referer"){
        echo '<script>';
        echo 'alert("'.$msg.'");';

        if($link == "referer"){
            if(isset($_SERVER["HTTP_REFERER"]))
                echo 'location.href=document.referrer;';
            else
                echo 'location.href="http://uraman.m-hosting.kr/ex_cms/";';
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

    // 비정상적인 접근일 경우 google로 리다이렉트하는 기능
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

    // 회원임을 표시하는 아이콘
    function member_icon($authority){
        $icon = "<span style='
            font-size:10px;
            color:white;
            border-radius: 3px;
            padding:2px 3px;
            margin:0 2px;
            letter-spacing:-1px;";
        switch($authority){
            case "member":
                $icon = $icon."background-color: #888;'>회원</span>";
                break;
            case "admin":
                $icon = $icon."background-color: #ff7530;'>관리</span>";
                break;
            case "super_admin":
                $icon = $icon."background-color: #ff7530;'>관리</span>";
                break;
        }
        return $icon;
    }

    function get_authority($id){
        return sql_get_row(sql_query("SELECT is_admin FROM CMS_userinfo WHERE id=".$id))["is_admin"];
    }

    // 로그인한 회원의 닉네임을 아이콘과 함께 가져옴
    function get_login_nickname($icon = true){
        if(isset($_SESSION['login'])){
            $id = $_SESSION['login'];
            $sql = "SELECT nickname,is_admin FROM CMS_userinfo WHERE id='".$id."'";
            $result = sql_get_row(sql_query($sql));
            $nickname = $result['nickname'];
            if($icon)
                $nickname = $nickname.member_icon($result['is_admin']);
            return $nickname;
        }
        else{
            return NULL;
        }
    }

    // 텍스트가 최소~최대 글자수에 맞는지 체크
    function strlen_check($text, $minlength, $maxlength){
        if(mb_strlen($text) < $minlength || mb_strlen($text) > $maxlength){
            return false;
        }
        return true;
    }

    // 멤버의 권한 체크
    function access_check($authority){
        $level = "";
        if(!isset($_SESSION["login"]))
            $level = "guest";
        else {
            $level = sql_get_row(sql_query("SELECT is_admin FROM CMS_userinfo WHERE id=".$_SESSION["login"]))["is_admin"];
        }

        switch($authority){
            case "member":
                if($level == "guest")
                    return false;
                return true;
            case "admin":
                if($level == "guest" || $level == "member")
                    return false;
                return true;
            case "super_admin":
                if($level == "super_admin")
                    return true;
                return false;
        }
        return true;
    }

    function authority_kor($authority){
        switch($authority){
            case "member":
                return "회원";
            case "admin":
                return "관리자";
            case "super_admin":
                return "최고 관리자";
            default:
                return "전체";
        }
    }

    function ban_check(){
        if(isset($_SESSION["login"])){
            global $conn;
            $sql = "SELECT end_date FROM CMS_user_banlist WHERE user_id=".$_SESSION["login"];
            $result = sql_query($sql);
            $row = sql_get_row($result);

            if(!is_null($row) && $row["end_date"] > date("Y-m-d H:i:s")){
                unset($_SESSION['login']);
                $msg = "활동 정지된 계정입니다. \\n ".$row["end_date"]."에 정지가 해제됩니다.";
                echo '<script>';
                echo 'alert("'.$msg.'");';
                echo 'location.href="http://uraman.m-hosting.kr/ex_cms/";';
                echo '</script>';
                exit;
            }
        }
    }
?>
