<?php
    $sql = "SELECT * FROM CMS_userinfo";

    $page = 1;
    if(isset($_GET["page"]))
        $page = $_GET["page"];
    $post_by_page = 10;
    $page_interval = 10;

    $total_post = sql_get_num_rows(sql_query($sql));
    $total_page = intval(($total_post-1) / $post_by_page) + 1;

    $members = sql_query($sql." ORDER BY is_admin DESC, join_date ASC LIMIT ".(($page-1) * $post_by_page).",".$post_by_page);
?>

<style>
    #mypage-posts{padding:30px 0;}
    #mypage-posts table{font-size:13px; width:100%; border-collapse: collapse;
        border-top:2px solid #aaa; border-bottom:2px solid #aaa; margin-top:10px}
    #mypage-posts tr{height:30px}
    #mypage-posts thead {border-bottom:2px solid #aaa;}
    #mypage-posts tbody tr{border-bottom:1px solid #aaa;}
    #mypage-posts .chkbox{width:30px; text-align: center;}
    #mypage-posts .authority{width:80px; text-align: center;}
    #mypage-posts .name{width:180px; text-align: center;}
    #mypage-posts .email{width:180px; text-align: center;}
    #mypage-posts .date{width:80px; text-align: center;}
    #mypage-posts td.date{font-size:12px}
    #mypage-posts .posts{width:60px; text-align: center;}
    #mypage-posts .cmts{width:60px; text-align: center;}
    #mypage-posts #buttons{text-align: right; margin:5px;}
    #mypage-posts .msg{font-weight: bold; font-size:13px;}
</style>

<div id="mypage-posts">
<span class="msg">전체 회원 수: <?echo $total_post?></span>
<form id="member_action_form" method="POST" action="process/_delete_process.php">
<table>
    <thead>
        <tr>
            <th class="chkbox"><input type="checkbox" class="checkbox" onchange="check_all(this)" id="all"></th>
            <th class="authority">권한</th>
            <th class="name">닉네임(아이디)</th>
            <th class="email">이메일</th>
            <th class="date">가입일</th>
            <th class="posts">게시글 수</th>
            <th class="cmts">댓글 수</th>
        </tr>
    </thead>
    <tbody>
    <?
        while ($row = sql_get_row($members)){
            echo "
            <tr>
                <td class='chkbox'><input type='checkbox' class='checkbox' onchange='check(this)' name='checked[]' value='".$row["id"]."'></td>
                <td class='authority'>".authority_kor($row["is_admin"])."</th>
                <td class='name'>".$row["nickname"]."(".$row["user_id"].")"."</th>
                <td class='email'>".$row["email"]."</th>
                <td class='date'>".$row["join_date"]."</th>
                <td class='posts'></th>
                <td class='cmts'></th>
            </tr>
            ";
        }
    ?>
    </tbody>
</table>
<div id="buttons">
    <input id="select_delete" style="font-size:13.333px" type="submit" class="btn-mini bg-gray" name="submit_button" value="활동 정지">
    <input id="all_delete" style="font-size:13.333px" type="submit" class="btn-mini bg-gray" onclick="return confirm('정말로 모든 게시글을 삭제하시겠습니까?')" name="submit_button" value="강제 탈퇴">
</div>
<input type="hidden" name="type" value="post">
</form>
<div id="page-buttons">
    <?
        $exist_prev_page = false;
        $exist_next_page = false;

        $page_group_start = intval(($page-1) / $page_interval) * $page_interval + 1;

        if($page > $page_interval)
            $exist_prev_page = true;

        if($total_page >= $page_group_start + $page_interval)
            $exist_next_page = true;

        $num = 0;
        while($num < $page_interval){
            $page_now = $page_group_start + $num;

            if($exist_prev_page && $num == 0){
                echo '<a href="?tab=posts&page='.($page_now-1).'"><</a>';
            }

            if($page_now > $total_page)
                break;

            if($page == $page_now)
                echo '<a class="selected">'.$page_now.'</a>';
            else
                echo '<a href="?tab=posts&page='.$page_now.'">'.$page_now.'</a>';
            $num += 1;

            if($exist_next_page && $num == $page_interval){
                echo '<a href="?tab=posts&page='.($page_now+1).'">></a>';
            }
        }
    ?>
</div>
</div>

<script>
    chkbox = $("input.checkbox");

    function check_all(box){
        for(i = 0; i < chkbox.length; i++){
            chkbox[i].checked = box.checked;
        }
    }

    function check(box){
        var all_check = true;
        for(i = 0; i < chkbox.length; i++){
            if(chkbox[i].id == "all")
                continue;
            if(chkbox[i].checked  == false){
                all_check = false;
                break;
            }
        }
        $("input#all")[0].checked = all_check;
    }

    $("#select_delete")[0].addEventListener("click",function(event){
        var all_check = false;
        for(i = 0; i < chkbox.length; i++){
            if(chkbox[i].id == "all")
                continue;
            if(chkbox[i].checked  == true){
                all_check = true;
                break;
            }
        }
        if(all_check == false){
            alert("선택된 게시글이 없습니다.")
            event.preventDefault();
        }
        else{
            if(confirm("선택한 게시글을 삭제하시겠습니까?")){
                $("#delete_post_form")[0].submit();
            }
            else{
                event.preventDefault();
            }
        }
    });

</script>
