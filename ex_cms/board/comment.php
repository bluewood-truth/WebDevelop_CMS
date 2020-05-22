<?php
    $sql = "SELECT * FROM CMS_comment_".$_GET["id"]." WHERE post_id=".$_GET["pid"];
    $comments = sql_query($sql);

    $admin_logined = access_check("admin");
 ?>
<form action="_admin_comment_delete_process.php" method="post">
<h3 style="margin:0">
    <?//if($admin_logined) echo '<input type="checkbox" onchange="cmt_check_all(this)" id="cmt_all">'?>
    Comments
</h3>
<ul id="comment-container">
    <?
        while($cmt = sql_get_row($comments)){
            $author = $cmt["guest_name"];
            if(is_null($author)){
                $author = $cmt["author_nickname"].member_icon(get_authority($cmt["author_id"]));
            }

            $is_mine = false;
            if(isset($_SESSION["login"])){
                if($_SESSION["login"] == $cmt['author_id'])
                    $is_mine = true;
            }

            $is_guest = "guest";
            if(is_null($cmt['guest_name'])){
                $is_guest = "member";
            }

            echo'
            <li><div class="cmt" id="cid'.$cmt['id'].'">
                    <div class="cmt-info">
                        <span class="cmt-name">'.$author.'</span><span class="cmt-date">'.$cmt['write_date'].'</span>
                        <span class="cmt-btns">';
                            //<a href="#">답글</a>';
            if(!is_null($cmt["guest_name"]) || $is_mine || access_check("admin")){
            echo'
                            <a name="'.$_GET["id"].'/'.$cmt['id'].'/edit_cmt/'.$is_guest.'" onclick="comment_password_check(this)">수정</a>
                            <a name="'.$_GET["id"].'/'.$cmt['id'].'/delete_cmt/'.$is_guest.'" onclick="comment_password_check(this)">삭제</a>'; }
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
</form>
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
            <textarea maxlength=512 name="cmt-write-content" rows="3" cols="80" required="required"></textarea>
        </div>
        <div class="comment-write-btn">
            <input type="submit" name="" value="등록">
        </div>
    </div>
    <div class="hidden-data" id=<?echo $_GET["id"]."/".$_GET["pid"]?>></div>
</form>

<script>
    var cmt_btn = $("#comment-write-container div.comment-write-btn input")[0];
    var get = $("#comment-write-container .hidden-data")[0].id.split("/");
    cmt_btn.addEventListener("click",function(){
            $("#comment-write-container")[0].action += "?board="+get[0]+"&post="+get[1];
    });

    function comment_password_check(btn){
        var info = btn.name.split("/");
        var link = "http://uraman.m-hosting.kr/ex_cms/board/password_check/?id="+info[0]+"&cid="+info[1]+"&action="+info[2]+"&pid=<?echo $_GET["pid"]?>";

        if((info[3] == "member" && info[2] == "delete_cmt") || is_admin()){
            if(confirm("댓글을 삭제하시겠습니까?"))
                location.href=link;
        }
        else{
            location.href=link;
        }
    }

    // var cmt_chkbox = $("input.cmt-checkbox");
    //
    // function cmt_check_all(box){
    //     for(i = 0; i < cmt_chkbox.length; i++){
    //         cmt_chkbox[i].checked = box.checked;
    //     }
    // }
    //
    // function cmt_check(box){
    //     var all_check = true;
    //     for(i = 0; i < cmt_chkbox.length; i++){
    //         if(cmt_chkbox[i].id == "all")
    //             continue;
    //         if(cmt_chkbox[i].checked  == false){
    //             all_check = false;
    //             break;
    //         }
    //     }
    //     $("input#cmt_all")[0].checked = all_check;
    // }



</script>
