<?php
    $post = sql_get_row(sql_query("SELECT * FROM CMS_post_".$_GET["id"]." WHERE id='".$_GET["post"]."'"));

    $result = sql_query("SELECT category_list FROM CMS_board WHERE 'id'='".$_GET['id']."'");
    $tmp = sql_get_row($result);
    $is_categorical = is_null($tmp["category_list"]) == false;

    $author = $post["guest_name"];
    if(is_null($author)){
        $author = $post["author_nickname"].member_icon();
    }

    $cmt = sql_query("SELECT id FROM CMS_comment_".$_GET["id"]." WHERE post_id='".$_GET["post"]."'");
    $cmt_num = sql_get_num_rows($cmt);
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
        <button type="button" class="btn-mini bg-gray">수정</button>
        <button type="button" class="btn-mini bg-gray">삭제</button>
        <button type="button" class="btn-mini bg-orange">글쓰기</button>
    </div>
</article>
