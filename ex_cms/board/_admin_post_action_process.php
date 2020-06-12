<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    // 관리자가 아니면 kick
    if(access_check("admin") == false)
        kick(0);

    if(!isset($_POST["submit_button"]))
        kick(11);

    if(!isset($_POST["checked"]))
        kick(12);

    $type;
    if($_POST["submit_button"] == "선택 삭제")
        $type = "delete";
    else if ($_POST["submit_button"] == "확인")
        $type = "move";
    else
        kick(13);

    $bid = $_SESSION["board_id"];

    if($type == "delete"){
        $arr = $_POST["checked"];

        for($i = 0; $i < count($arr); $i++){
            $pid = $arr[$i];

            // 게시글이 존재하는지 체크
            $sql = "SELECT author_id FROM CMS_post_".$bid." WHERE id=".$pid;
            $result = sql_query($sql);
            if(sql_get_num_rows($result) == 0)
                continue; // 존재하지 않는 글

            sql_query("DELETE FROM CMS_post_".$bid." WHERE id=".$pid);
            $sql = "DELETE FROM CMS_post_check WHERE board_id='".$bid."' AND post_id=".$pid;
            sql_query($sql);
        }

    }
    else if ($type == "move"){
        $arr = $_POST["checked"];

        for($i = 0; $i < count($arr); $i++){
            $pid = $arr[$i];

            $destination_board = $_POST["boards"];
            $category;
            if(isset($_POST["categories"]))
                $category = filter($_POST["categories"]);
            else
                $category = null;
                
            if(sql_table_exist_check($destination_board) == false){
                kick("이동할 게시판이 존재하지 않음");
            }

            // 게시글이 존재하는지 체크
            $sql = "SELECT * FROM CMS_post_".$bid." WHERE id=".$pid;
            $result = sql_query($sql);
            if(sql_get_num_rows($result) == 0)
                continue; // 존재하지 않는 글

            $row = sql_get_row($result);
            $sql = "INSERT INTO CMS_post_".$destination_board." (title,content,author_id,author_nickname,guest_name,guest_password,category,write_date)
                    SELECT title,content,author_id,author_nickname,guest_name,guest_password,'".$category."' as category,write_date FROM CMS_post_".$bid." WHERE id=".$pid;
            sql_query($sql);
            $new_post_id = sql_insert_key();
            $sql = "UPDATE CMS_post_check SET board_id='".$destination_board."',post_id='".$new_post_id."' WHERE board_id='".$bid."' AND post_id=".$pid;
            sql_query($sql);

            $sql = "SELECT id,content,author_id,author_nickname,guest_name,guest_password,write_date,post_id FROM CMS_comment_".$bid." WHERE post_id=".$pid;
            $result = sql_query($sql);
            while($row = sql_get_row($result)){
                $sql = "INSERT INTO CMS_comment_".$destination_board." (content,author_id,author_nickname,guest_name,guest_password,write_date,post_id)
                        VALUES('".$row['content']."','".$row["author_id"]."','".$row["author_nickname"]."','".$row["guest_name"]."','".$row["guest_password"]."','".$row["write_date"]."','".$new_post_id."')";
                sql_query($sql);
                $sql = "UPDATE CMS_comment_check SET board_id='".$destination_board."',comment_id='".sql_insert_key()."',post_id='".$new_post_id."' WHERE board_id='".$bid."' AND comment_id=".$row["id"];
                sql_query($sql);
            }
            sql_query("DELETE FROM CMS_post_".$bid." WHERE id=".$pid);
            sql_query("DELETE FROM CMS_comment_".$bid." WHERE id=".$pid);
        }
    }
    else kick(14);

    header("Location:http://uraman.m-hosting.kr/ex_cms/board/?id=".$bid);
    exit;
 ?>
