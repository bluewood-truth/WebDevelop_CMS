<?php
    $page = 1;
    if(isset($_GET["page"]))
        $page = $_GET["page"];
    $post_by_page = 10;
    $page_interval = 10;

    $sql = "SELECT u.id, u.user_id, nickname, is_admin, deleted, end_date
    FROM CMS_userinfo AS u LEFT JOIN CMS_user_banlist AS b ON b.user_id = u.id";
    if(isset($_GET["search_type"]) && isset($_GET["keyword"])){
        switch($_GET["search_type"]){
            case "id":
                $sql = $sql.' WHERE user_id LIKE "%'.$_GET["keyword"].'%"';
                break;
            case "nickname":
                $sql = $sql.' WHERE nickname LIKE "%'.$_GET["keyword"].'%"';
                break;
        }
        $sql = $sql." AND deleted=1 OR end_date > now()";
    }
    else{
        $sql = $sql." WHERE deleted=1 OR end_date > now()";
    }
    $total_post = sql_get_num_rows(sql_query($sql));
    $total_page = intval(($total_post-1) / $post_by_page) + 1;

    $members = sql_query($sql." ORDER BY deleted ASC, end_date DESC LIMIT ".(($page-1) * $post_by_page).",".$post_by_page);

    $my_authority = sql_get_row(sql_query("SELECT is_admin FROM CMS_userinfo WHERE id=".$_SESSION["login"]))["is_admin"];
?>

<style>
    #admin-members{padding:30px 0;}
    #admin-members table{font-size:13px; width:100%; border-collapse: collapse;
        border-top:2px solid #aaa; border-bottom:2px solid #aaa; margin-top:10px}
    #admin-members tr{height:30px}
    #admin-members thead {border-bottom:2px solid #aaa;}
    #admin-members tbody tr{ height:45px; border-bottom:1px solid #aaa;}
    #admin-members .chkbox{width:30px; text-align: center;}
    #admin-members .authority{width:80px; text-align: center;}
    #admin-members .name{width:180px; text-align: center;}
    #admin-members .type{width:180px; text-align: center;}
    #admin-members #buttons{text-align: right; margin:5px;}
    #admin-members .msg{font-weight: bold; font-size:13px;}

    .button-submenu{display:inline-block; padding:5px}
    .button-submenu select{ height:30px}
    .button-submenu input{ height:30px}

</style>

<div id="admin-members">
<span class="msg">정지/탈퇴 회원 수: <?echo $total_post?></span>
<form id="member_action_form" method="POST" action="process/_member_action.php">
<table>
    <thead>
        <tr>
            <th class="chkbox"><input type="checkbox" class="checkbox" onchange="check_all(this)" id="all"></th>
            <th class="authority">권한</th>
            <th class="name">닉네임(아이디)</th>
            <th class="type">구분</th>
        </tr>
    </thead>
    <tbody>
    <?
        while ($row = sql_get_row($members)){
            $show_checkbox = true;

            // 탈퇴했으면 안띄움
            if($row["deleted"] == 1)
                $show_checkbox = false;

            $type = "";
            if($row["deleted"] == 1){
                $type="탈퇴";
            }else{
                $type="활동정지<br>(".$row["end_date"]." 까지)";
            }
            echo "
            <tr>
                <td class='chkbox'>";
            if($show_checkbox) echo "<input type='checkbox' class='checkbox' onchange='check(this)' name='checked[]' value='".$row["id"]."'>";
            echo "</td>
                <td class='authority'>".authority_kor($row["is_admin"])."</td>
                <td class='name'>".$row["nickname"]."(".$row["user_id"].")"."</td>
                <td class='type'>".$type."</td>
            </tr>
            ";
        }
    ?>
    </tbody>
</table>
<div id="buttons">
    <input id="select_release_ban" style="font-size:13.333px" type="submit" name="release_ban" class="btn-mini bg-gray" value="정지 해제">
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
<div id="search-box">
    <form method="GET">
        <input class="hidden-data" name="tab" value="<?echo $_GET["tab"]?>">
        <select name="search_type">
            <option value="id" <?if(isset($_GET["search_type"]))if($_GET["search_type"]=="id") echo 'selected';?>>아이디</option>
            <option value="nickname" <?if(isset($_GET["search_type"]))if($_GET["search_type"]=="nickname") echo 'selected';?>>닉네임</option>
        </select>
        <input type="text" name="keyword" value="<?if(isset($_GET["keyword"])) echo $_GET["keyword"];?>">
        <input id="search-btn" type="submit" value="검색">
    </form>
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

    $("#select_release_ban")[0].addEventListener("click",function(event){
        var check = false;
        for(i = 0; i < chkbox.length; i++){
            if(chkbox[i].id == "all")
                continue;
            if(chkbox[i].checked  == true){
                check = true;
                break;
            }
        }
        if(check == false){
            alert("선택된 멤버가 없습니다.")
            event.preventDefault();
        }
        else{
            if(confirm("선택한 멤버의 정지를 해제시키겠습니까?")){

            }
            else{
                event.preventDefault();
            }
        }
    });

</script>
