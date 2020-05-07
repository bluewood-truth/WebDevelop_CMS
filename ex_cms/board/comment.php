<?php
    $sql = "SELECT * FROM CMS_comment_".$_GET["id"]." WHERE post_id=".$_GET["post"];
    $comments = sql_query($sql);
 ?>

<h3 style="margin:0">Comments</h3>
<ul id="comment-container">
    <?
        while($cmt = sql_get_row($comments)){
            $author = $cmt["guest_name"];
            if(is_null($author)){
                $author = $cmt["author_nickname"].member_icon();
            }

            $is_mine = false;
            if(isset($_SESSION["login"])){
                if($_SESSION["login"] == $cmt['author_id'])
                    $is_mine = true;
            }

            echo'
            <li>
                <div class="cmt" id="cid'.$cmt['id'].'">
                    <div class="cmt-info">
                        <span class="cmt-name">'.$author.'</span><span class="cmt-date">'.$cmt['write_date'].'</span>
                        <span class="cmt-btns">
                            <a href="#">답글</a>';
            if(!is_null($cmt["guest_name"]) || $is_mine )
                echo'
                                <a href="http://uraman.m-hosting.kr/ex_cms/board/password_check?id='.$_GET['id'].'&cid='.$cmt['id'].'&action=edit_cmt">수정</a>
                                <a href="http://uraman.m-hosting.kr/ex_cms/board/password_check?id='.$_GET['id'].'&cid='.$cmt['id'].'&action=delete_cmt">삭제</a>';
            echo'
                        </span>
                    </div>
                    <div class="cmt-content">'.$cmt['content'].'</div>
                </div>
            </li>
            ';
        }
    ?>


</ul>
<form id="comment-write-container" method="post" action="_comment_write_process.php">
    <div class="comment-write-box">
        <div class="comment-write-head">
            <?
                if($nickname = get_login_nickname())
                    echo '<div class="cmt-name long">'.$nickname.'</div>';
                else{
                    echo '
                    <input type="text" name="cmt-write-name" id="cmt-write-name" minlength=2 maxlength=8 placeholder="닉네임" value="" required="required">
                    <input type="password" name="cmt-write-pw" id="cmt-write-pw" minlength=2 maxlength=16 placeholder="패스워드" value="" required="required">
                    ';
                }
            ?>
        </div>
        <div class="comment-write-textarea">
            <textarea name="cmt-write-content" rows="3" cols="80" required="required"></textarea>
        </div>
        <div class="comment-write-btn">
            <input type="submit" name="" value="등록">
        </div>
    </div>
    <div class="hidden-data" id=<?echo $_GET["id"]."/".$_GET["post"]?>></div>
</form>

<script>
    var cmt_btn = $("#comment-write-container div.comment-write-btn input")[0];
    var get = $("#comment-write-container .hidden-data")[0].id.split("/");
    cmt_btn.addEventListener("click",function(){
            $("#comment-write-container")[0].action += "?board="+get[0]+"&post="+get[1];
    });
</script>
