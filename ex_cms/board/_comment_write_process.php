<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();
    header('Content-Type: text/html; charset=utf-8');
    // 댓글 내용이 없으면 kick
    $text = preg_replace('/\s+/', '',$_POST["cmt-write-content"]);
    if(empty($text)){
        referer();
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

        $content = filter($_POST["cmt-write-content"],true);
        $post_id = $_GET["post"];
        $author_id = $member["id"];
        $author_nickname = $member["nickname"];

        $sql = "INSERT INTO `CMS_comment_".$_GET["board"]."` (content,author_id,author_nickname,post_id,write_date) VALUES('".$content."','".$author_id."','".$author_nickname."','".$post_id."',now())";
        sql_query($sql);
        referer();
        exit;
    }

    // 게스트일 경우
    else{
        // 닉네임/비밀번호가 없거나 비어있으면 kick
        if(!isset($_POST['cmt-write-name']) || !isset($_POST['cmt-write-pw']))
            kick(2);
        if(empty($_POST['cmt-write-name']) || empty($_POST['cmt-write-pw']))
            kick(3);

        // 글자수 안맞으면 kick
        $check_name = "/^.{2,8}$/";
        $check_pw = "/^.{2,16}$/";
        if(preg_match($check_name,$_POST["cmt-write-name"]) == False || preg_match($check_pw,$_POST["cmt-write-pw"]) == False)
            kick(4);

        $name = filter($_POST["cmt-write-name"]);
        $pw = sha1($_POST["cmt-write-pw"]);
        $content = filter($_POST["cmt-write-content"],true);
        $post_id = $_GET["post"];

        $sql = "INSERT INTO `CMS_comment_".$_GET["board"]."` (content,guest_name,guest_password,post_id,write_date) VALUES('".$content."','".$name."','".$pw."','".$post_id."',now())";
        sql_query($sql);
        referer();
        exit;
    }
 ?>
