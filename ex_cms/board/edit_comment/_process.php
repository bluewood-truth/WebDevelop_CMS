<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    // 게시판 id와 코멘트 id가 없으면 kick
    if(!isset($_GET["id"]) || !isset($_GET["cid"]) )
        kick(1);

    // prev_page가 없으면 kick (패스워드 체크 페이지를 넘어욌으면 있어야 함)
    if(!isset($_SESSION["prev_page"]))
        kick(2);

    $id = $_GET['id'];
    $cid = $_GET['cid'];
    $content = filter($_POST["cmt-write-content"],true);

    $sql = "SELECT * FROM CMS_comment_".$id." WHERE id=".$cid;
    $result = sql_query($sql);
    if(sql_get_num_rows($result) == 0)
        invalid_access("존재하지 않는 댓글입니다.",$_SESSION["prev_page"]);

    $row = sql_get_row($result);
    
    // 이 밑으로는 관리자가 아닐 때만 체크
    if(access_check("admin") == false){


        // 로그인했고 회원 댓글일 경우
        if(isset($_SESSION["login"]) == true && !is_null($row['author_id'])){
            // 자기 댓글이 아니면 kick
            if($row['author_id'] != $_SESSION["login"]){
                kick(3);
            }
        }
        // 로그인 안했거나 게스트로 쓴 댓글인 경우
        else {
            if(!isset($_SESSION["password"])){
                kick(4);
            }
            $password = sha1($_SESSION["password"]);
            unset($_SESSION["password"]);

            if($row['guest_password'] != $password){
                kick(5);
            }
        }
    }

    $sql = "UPDATE CMS_comment_".$id." SET content = '".$content."' WHERE id =".$cid;
    sql_query($sql);

    header("Location:".$_SESSION["prev_page"]);
    unset($_SESSION["prev_page"]);
 ?>
