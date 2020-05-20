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

    // 제목 글자수 안맞으면 kick
    if(strlen_check($_POST["title"],1,40) == false){
        kick(01);
    }

    $pid = "";

    if($_SESSION["action"] == "write_post"){
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
            if(strlen_check($_POST["post-write-name"],2,8) == False || strlen_check($_POST["post-write-pw"],2,16) == False)
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
    }
    else if ($_SESSION["action"] == "edit_post"){
        // 게시판 id와 포스트 id가 없으면 kick
        if(!isset($_GET["id"]) || !isset($_GET["pid"]) )
            kick(21);

        // prev_page가 없으면 kick (패스워드 체크 페이지를 넘어욌으면 있어야 함)
        if(!isset($_SESSION["prev_page"]))
            kick(22);

        $id = $_GET['id'];
        $pid = $_GET['pid'];
        $category = null;
        if(isset($_POST["category"])){
            $category = filter($_POST["category"]);
        }
        $title = filter($_POST["title"]);
        $content = $_POST["ir1"];

        $sql = "SELECT * FROM CMS_post_".$id." WHERE id=".$pid;
        $result = sql_query($sql);
        if(sql_get_num_rows($result) == 0)
            invalid_access("존재하지 않는 글입니다.",$_SESSION["prev_page"]);

        $row = sql_get_row($result);

        // 이 밑으로는 관리자가 아닐 때만 체크
        if(access_check("admin") == false){
            // 로그인했고 회원 글일 경우
            if(isset($_SESSION["login"]) == true && !is_null($row['author_id'])){
                // 자기 글이 아니면 kick
                if($row['author_id'] != $_SESSION["login"]){
                    kick(23);
                }
            }
            // 로그인했고 비회원 글일 경우
            else if(isset($_SESSION["login"]) == true && is_null($row['author_id'])){

            }
            // 로그인 안했을 경우
            else {
                if(!isset($_SESSION["password"])){
                    kick(24);
                }
                $password = sha1($_SESSION["password"]);
                unset($_SESSION["password"]);

                if($row['guest_password'] != $password){
                    kick(25);
                }
            }
        }

        $sql = "UPDATE CMS_post_".$id." SET title='".$title."',content='".$content."',category='".$category."' WHERE id =".$pid;
        sql_query($sql);
    }

    header("Location:http://uraman.m-hosting.kr/ex_cms/board/?id=".$_GET["id"]."&pid=".$pid);
    unset($_SESSION["action"]);
    exit;
 ?>
