<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    // 게시판 id와 포스트 id가 없으면 kick
    if(!isset($_GET["id"]) || !isset($_GET["pid"]) )
        kick(1);

    // prev_page가 없으면 kick (패스워드 체크 페이지를 넘어욌으면 있어야 함)
    if(!isset($_SESSION["prev_page"]))
        kick(2);

    $id = $_GET['id'];
    $pid = $_GET['pid'];

    $sql = "SELECT * FROM CMS_post_".$id." WHERE id=".$pid;
    $result = sql_query($sql);
    if(sql_get_num_rows($result) == 0)
        invalid_access("존재하지 않는 댓글입니다.",$_SESSION["prev_page"]);

    // 이 밑으로는 관리자가 아닐 때만 체크
    if(access_check("admin") == false){
        $row = sql_get_row($result);

        // 로그인했고 회원 글일 경우
        if(isset($_SESSION["login"]) == true && !is_null($row['author_id'])){
            // 자기 댓글이 아니면 kick
            if($row['author_id'] != $_SESSION["login"]){
                kick(3);
            }
        }
        // 로그인 안했거나 게스트로 쓴 댓글인 경우
        else {
            if(!isset($_POST["password"]))
                kick(4);
            $password = sha1($_POST["password"]);

            if($row['guest_password'] != $password)
                invalid_access("비밀번호가 일치하지 않습니다.",$_SESSION["prev_page"]);
        }
    }

    $sql = "DELETE FROM CMS_post_".$id." WHERE id = ".$pid;
    sql_query($sql);
    $sql = "DELETE FROM CMS_post_check WHERE board_id='".$id."' AND post_id=".$pid;
    sql_query($sql);
    $sql = "DELETE FROM CMS_comment_check WHERE board_id='".$id."' AND post_id=".$pid;
    sql_query($sql);

    header("Location:http://uraman.m-hosting.kr/ex_cms/board/?id=".$id);
    unset($_SESSION["prev_page"]);
 ?>
