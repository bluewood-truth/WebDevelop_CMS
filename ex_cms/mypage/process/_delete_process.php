<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    if(!isset($_POST["submit_button"]) || !isset($_POST["type"]))
        kick(11);

    $type = $_POST["type"];

    // type은 post나 comment 중 하나여야 함
    if($type != "post" && $type != "comment")
        kick(13);

    if($_POST["submit_button"] == "선택 삭제"){
        if(!isset($_POST["checked"]))
            kick(12);

        $arr = $_POST["checked"];

        for($i = 0; $i < count($arr); $i++){
            $post_info = explode("/",$arr[$i]);
            $bid = $post_info[0];
            $pid = $post_info[1];

            // 게시글이 존재하는지, 해당 회원의 게시글이 맞는지 체크
            $sql = "SELECT author_id FROM CMS_".$type."_".$bid." WHERE id=".$pid;
            $result = sql_query($sql);

            if(sql_get_num_rows($result) == 0)
                continue; // 존재하지 않는 글
            if(sql_get_row($result)["author_id"] != $_SESSION["login"])
                kick(1); // 해당 회원의 것이 아닌 글

            sql_query("DELETE FROM CMS_".$type."_".$bid." WHERE id=".$pid);
            $sql = "DELETE FROM CMS_".$type."_check WHERE board_id='".$bid."' AND ".$type."_id=".$pid;
            sql_query($sql);
        }
    }
    else if ($_POST["submit_button"] == "전체 삭제"){
        $sql = "SELECT id FROM CMS_board";
        $result = sql_query($sql);

        while($row = sql_get_row($result)){
            $sql = "DELETE FROM CMS_".$type."_".$row["id"]." WHERE author_id =".$_SESSION["login"];
            sql_query($sql);
            $sql = "DELETE FROM CMS_".$type."_check WHERE member_id=".$_SESSION["login"];
            sql_query($sql);
        }
    }
    else kick(13);

    if($type == "comment")
        $type = "cmt";

    invalid_access("삭제되었습니다.", "http://uraman.m-hosting.kr/ex_cms/mypage/?tab=".$type."s");
 ?>
