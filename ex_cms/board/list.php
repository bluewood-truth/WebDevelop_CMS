<?php
    $page = 1;
    if(isset($_GET["page"]))
        $page = $_GET["page"];
    $post_by_page = 10;
    $page_interval = 10;

    $sql = "SELECT id,category,title,author_id,author_nickname,guest_name,write_date,views,recommends from CMS_post_".$_GET['id'];
    if(isset($_GET["search_type"]) && isset($_GET["keyword"])){
        switch($_GET["search_type"]){
            case "title":
                $sql = $sql.' WHERE title LIKE "%'.$_GET["keyword"].'%"';
                break;
            case "content":
                $sql = $sql.' WHERE content LIKE "%'.$_GET["keyword"].'%"';
                break;
            case "category":
                $sql = $sql.' WHERE category LIKE "%'.$_GET["keyword"].'%"';
                break;
            case "title+content":
                $sql = $sql.' WHERE title LIKE "%'.$_GET["keyword"].'%" OR content LIKE "%'.$_GET["keyword"].'%"';
                break;
            case "author":
                $sql = $sql.' WHERE author_nickname LIKE "%'.$_GET["keyword"].'%" OR guest_name LIKE "%'.$_GET["keyword"].'%"';
                break;
        }
    }

    $total_post = sql_get_num_rows(sql_query($sql));
    $total_page = intval(($total_post-1) / $post_by_page) + 1;

    $sql = $sql." ORDER BY write_date DESC LIMIT ".(($page-1) * $post_by_page).",".$post_by_page;
    $table = sql_query($sql);

    $result = sql_query("SELECT * FROM CMS_board WHERE id='".$_GET['id']."'");
    $tmp = sql_get_row($result);
    $is_categorical = is_null($tmp["category_list"]) == false;

    $admin_logined = access_check("admin");

    $id = $_GET["id"];
    $_SESSION["board_id"] = $_GET["id"];
 ?>

<form method="POST" style="margin-bottom:5px" action="_admin_post_action_process.php">
<table class="board_list">
    <thead>
        <tr>
            <?
            if($admin_logined)
                echo '<th class="chkbox"><input type="checkbox" class="checkbox" onchange="check_all(this)" id="all"></th>';
            ?>
            <th class="board_num">번호</th>
            <? if($is_categorical) echo '<th class="board_cat">분류</th>';?>
            <th class="board_title">제목</th>
            <th class="board_author">글쓴이</th>
            <th class="board_date">작성일</th>
            <th class="board_views">조회</th>
            <th class="board_recommends">추천</th>
        </tr>
    </thead>
    <tbody>
        <?
            while($row = sql_get_row($table)){
                $author = $row["guest_name"];
                if(is_null($author)){
                    $author = $row["author_nickname"].member_icon(get_authority($row["author_id"]));
                }

                $date_tmp = date_create($row["write_date"]);
                $date = date_format($date_tmp,"Y-m-d");
                if($date == date("Y-m-d")){
                    $date = date_format($date_tmp,"H:i");
                }

                $cmt = sql_query("SELECT id FROM CMS_comment_".$_GET["id"]." WHERE post_id='".$row["id"]."'");
                $cmt_num = sql_get_num_rows($cmt);

                $url = "http://uraman.m-hosting.kr/ex_cms/board/?";
                $request = $_GET;
                $request["pid"] = $row["id"];
                $url = $url.http_build_query($request);

                echo '<tr>';
                if($admin_logined) echo "<td class='chkbox'><input type='checkbox' class='checkbox' onchange='check(this)' name='checked[]' value='".$row["id"]."'></td>";
                echo '<td class="board_num">'.$row["id"].'</td>';
                if($is_categorical) echo '<td class="board_cat">'.$row["category"].'</td>';
                echo '<td class="board_title"><a href="'.$url.'">'.$row["title"].'</a><span class="board-post-cmt">'.$cmt_num.'</span></td>';
                echo '<td class="board_author">'.$author.'</td>';
                echo '<td class="board_date">'.$date.'</td>';
                echo '<td class="board_views">'.$row["views"].'</td>';
                echo '<td class="board_recommends">'.$row["recommends"].'</td>';
                echo '</tr>';

            }
        ?>
    </tbody>
</table>
<style>
    .move-board-select{display:inline-block;background-color: #555; padding:5px}
    .move-board-select select{ height:30px}
    .move-board-select input{ height:30px}
</style>
<div class="post-bottom-buttons">
    <div style="float:left">
    <?
    if($admin_logined){
        echo '<input id="select_delete" style="font-size:13.33333px" type="submit" class="btn-mini bg-gray" name="submit_button" value="선택 삭제">';
        echo '<input id="select_move" style="font-size:13.33333px; margin-left:5px;" type="button" class="btn-mini bg-gray"value="선택 이동">';
    }
    ?>
    <div id="move_board" class="invisible">
        <select id="boards" name="boards" onchange="update_category(this.value)">
            <?
                $sql = "SELECT id,name_kor,category_list FROM CMS_board";
                $result = sql_query($sql);
                $category;
                while($row = sql_get_row($result)){
                    if($row["id"] == $id)
                        continue;
                    echo "<option value='".$row["id"]."'>".$row["name_kor"]."</option>";
                    $category[$row["id"]] = $row["category_list"];
                }
            ?>
        </select>
        <select id="cat" name="categories"></select>
        <input type="submit" name="submit_button" value="확인">
        <input type="button" value="취소" onclick="this.closest('div.move-board-select').classList.replace('move-board-select','invisible')">
    </div>
</div>
    <button id="post-write-button-bottom-list" type="button" class="btn-mini bg-orange">글쓰기</button>
</div>
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
                echo '<a href="?id='.$_GET["id"].'&page='.($page_now-1).'"><</a>';
            }

            if($page_now > $total_page)
                break;

            if($page == $page_now)
                echo '<a class="selected">'.$page_now.'</a>';
            else
                echo '<a href="?id='.$_GET["id"].'&page='.$page_now.'">'.$page_now.'</a>';
            $num += 1;

            if($exist_next_page && $num == $page_interval){
                echo '<a href="?id='.$_GET["id"].'&page='.($page_now+1).'">></a>';
            }
        }

    ?>
</div>
<div id="search-box">
    <form method="get">
        <input class="hidden-data" name="id" value="<?echo $_GET["id"]?>">
        <select name="search_type">
            <option value="title">제목</option>
            <option value="content">내용</option>
            <option value="title+content">제목+내용</option>
            <option value="author">글쓴이</option>
            <option value="category">분류</option>
        </select>
        <input type="text" name="keyword" value="">
        <input id="search-btn" type="submit" value="검색">
    </form>
</div>
<script>

    $("#post-write-button-bottom-list")[0].addEventListener("click",function(){
        location.href="http://uraman.m-hosting.kr/ex_cms/board/write_post/?id=<? echo $_GET["id"]; ?>";
    });

    for(var i = 0; i < $(".board_title a").length; i++){
        $(".board_title a")[i].innerText = text_cutting($(".board_title a")[i].innerText, 32);
    }

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
                $("#delete_cmt_form")[0].submit();
            }
            else{
                event.preventDefault();
            }
        }
    });

    $("#select_move")[0].addEventListener("click",function(event){
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
            $("#move_board")[0].classList.replace("invisible","move-board-select");
            update_category($("select#boards")[0].value);
        }
    });

    var categories = <? array_converter($category); ?>;
    var category_select = $("select#cat")[0];

    function update_category(board){
        if(categories[board] == null)
            category_select.classList.add("invisible");
        else{
            category_select.classList.remove("invisible");
            var cats = categories[board].split("|");
            var innerText = "";
            for(i = 0; i < cats.length; i++){
                var option = "<option value='"+cats[i]+"'>"+cats[i]+"</option>";
                innerText += option;
            }
            category_select.innerHTML = innerText;
        }
    }


</script>
