<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();
    header('Content-Type: text/html; charset=utf-8');

    // 글 내용이 없으면 kick
    $text = preg_replace('/\s+/', '',$_POST["ir1"]);
    if( $text == ""  || $text == null || $text == '&nbsp;' || $text == '<p>&nbsp;</p>' || $text == '<p><br></p>'){
        referer();
    }

    // action 세션이 없으면 kick
    if(!isset($_SESSION["action"])){
        kick(0);
    }

    // 로그인한 경우
    if(isset($_SESSION['login'])){

        // 세션 id에 해당하는 계정이 없으면 kick
        $sql = "SELECT id,nickname FROM CMS_userinfo WHERE id='".$_SESSION['login']."'";
        $member = sql_query($sql);
        if(sql_get_num_rows($member) == 0){
            kick(1);
        }

        $member = sql_get_row($member);

        $category = null;
        if(isset($_POST["category"])){
            $category = filter($_POST["category"]);
        }
        $title = filter($_POST["title"]);
        $content = $_POST["ir1"];
        $author_id = $member["id"];
        $author_nickname = $member["nickname"];

        $sql = "INSERT INTO `CMS_post_".$_GET["id"]."` (title,content,author_id,author_nickname,category,write_date)
                VALUES('".$title."','".$content."','".$author_id."','".$author_nickname."','".$category."',now())";
        sql_query($sql);
    }

    // 게스트일 경우
    else{
        // 닉네임/비밀번호가 없거나 비어있으면 kick
        if(!isset($_POST['post-write-name']) || !isset($_POST['post-write-pw']))
            kick(2);
        if(empty($_POST['post-write-name']) || empty($_POST['post-write-pw']))
            kick(3);

        // 글자수 안맞으면 kick
        $check_name = "/^.{2,8}$/";
        $check_pw = "/^.{2,16}$/";
        if(preg_match($check_name,$_POST["post-write-name"]) == False || preg_match($check_pw,$_POST["post-write-pw"]) == False)
            kick(4);

        $name = filter($_POST["post-write-name"]);
        $pw = sha1($_POST["post-write-pw"]);
        $category = null;
        if(isset($_POST["category"])){
            $category = filter($_POST["category"]);
        }
        $title = filter($_POST["title"]);
        $content = $_POST["ir1"];

        $sql = "INSERT INTO `CMS_post_".$_GET["id"]."` (title,content,guest_name,guest_password,category,write_date)
                VALUES('".$title."','".$content."','".$name."','".$pw."','".$category."',now())";
        sql_query($sql);
    }

    $pid = sql_get_row(sql_query("SELECT id from CMS_post_".$_GET['id']." ORDER BY id DESC"))['id'];

    header("Location:http://uraman.m-hosting.kr/ex_cms/board/?id=".$_GET["id"]."&post=".$pid);
    unset($_SESSION["action"]);
    exit;
 ?>
