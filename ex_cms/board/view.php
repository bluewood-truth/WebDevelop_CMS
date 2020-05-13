<?php
    $post = sql_get_row(sql_query("SELECT * FROM CMS_post_".$_GET["id"]." WHERE id='".$_GET["post"]."'"));
    if(is_null($post)){
        invalid_access("존재하지 않는 글입니다.","http://uraman.m-hosting.kr/ex_cms/board/?id=".$_GET["id"]);
    }

    $result = sql_query("SELECT category_list FROM CMS_board WHERE 'id'='".$_GET['id']."'");
    $tmp = sql_get_row($result);
    $is_categorical = is_null($tmp["category_list"]) == false;

    $author = $post["guest_name"];
    if(is_null($author)){
        $author = $post["author_nickname"].member_icon();
    }

    $cmt = sql_query("SELECT id FROM CMS_comment_".$_GET["id"]." WHERE post_id='".$_GET["post"]."'");
    $cmt_num = sql_get_num_rows($cmt);

    $editable = false;
    if(isset($_SESSION["login"])){
        if($_SESSION["login"] == $post["author_id"] || is_null($post["author_id"])){
            $editable = true;
        }
    }
    else{
        if(is_null($post["author_id"])){
            $editable = true;
        }
    }
 ?>
<article id="post">
    <div id="post-title-container">
        <div class="post-title-box">
            <? if($is_categorical) echo '<span id="post-category">['.$_post["category"].']</span>' ?>
            <span id="post-title"><? echo $post['title'] ?></span>
        </div>
        <div class="post-title-box">
            <span id="post-name"><? echo $author ?></span>
            <span id="post-date"><? echo $post["write_date"] ?></span>
            <span class="post-info-etc">조회 <? echo $post["views"] ?></span>
            <span class="post-info-etc">추천 <? echo $post["recommends"] ?></span>
            <span class="post-info-etc">댓글 <? echo $cmt_num ?></span>
        </div>
         <div class="spacer"></div>
    </div>
    <div id="post-content-container">
        <div id="post-content">
            <? echo $post["content"] ?>
        </div>
        <div id="post-recommend-box">
            <button type="button" class="btn-recommend">추천 <span class="up">0</span></button>
            <button type="button" class="btn-recommend">비추천 <span class="down">0</span></button>
        </div>
    </div>
    <div>
        <? include "comment.php" ?>
    </div>
    <div class="post-bottom-buttons">
        <?
            if($editable){
                echo '
                <button id="post-edit-button" type="button" class="btn-mini bg-gray">수정</button>
                <button id="post-delete-button" type="button" class="btn-mini bg-gray">삭제</button>';
            }
        ?>
        <button id="post-write-button" type="button" class="btn-mini bg-orange">글쓰기</button>
    </div>
</article>

<script>
    <?
    if($editable){
        echo '
        $("#post-write-button")[0].addEventListener("click",function(){
            location.href="http://uraman.m-hosting.kr/ex_cms/board/write_post/?id=/'.$_GET["id"].'";
        });
        $("#post-edit-button")[0].addEventListener("click",function(){
            location.href="http://uraman.m-hosting.kr/ex_cms/board/password_check/?id='.$_GET["id"].'&action=edit_post&pid='.$_GET["post"].'";
        });';
    }
    ?>
    $("#post-delete-button")[0].addEventListener("click",function(){
        location.href="http://uraman.m-hosting.kr/ex_cms/board/password_check/?id=<? echo $_GET["id"]; ?>&action=delete_post&pid=<?echo $_GET["post"];?>";
    });

</script>
