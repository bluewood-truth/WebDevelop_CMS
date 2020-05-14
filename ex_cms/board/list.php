<?php
    $page = 1;
    if(isset($_GET["page"]))
        $page = $_GET["page"];
    $post_by_page = 10;
    $page_interval = 10;

    $total_post = sql_get_num_rows(sql_query("SELECT * from CMS_post_".$_GET['id']));
    $total_page = intval(($total_post-1) / $post_by_page) + 1;

    $sql = "SELECT * from CMS_post_".$_GET['id']." ORDER BY id DESC LIMIT ".(($page-1) * $post_by_page).",".$post_by_page;
    $table = sql_query($sql);

    $result = sql_query("SELECT * FROM CMS_board WHERE id='".$_GET['id']."'");
    $tmp = sql_get_row($result);
    $is_categorical = is_null($tmp["category_list"]) == false;
 ?>

<table class="board_list">
    <thead>
        <tr>
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
                    $author = $row["author_nickname"].member_icon();
                }

                $date_tmp = date_create($row["write_date"]);
                $date = date_format($date_tmp,"Y-m-d");
                if($date == date("Y-m-d")){
                    $date = date_format($date_tmp,"H:i");
                }

                $cmt = sql_query("SELECT id FROM CMS_comment_".$_GET["id"]." WHERE post_id='".$row["id"]."'");
                $cmt_num = sql_get_num_rows($cmt);

                echo '
                <tr>
                    <td class="board_num">'.$row["id"].'</td>';
                if($is_categorical) echo '<td class="board_cat">'.$row["category"].'</td>';
                echo '<td class="board_title"><a href="http://uraman.m-hosting.kr/ex_cms/board/?id='.$_GET["id"].'&page='.$page.'&pid='.$row["id"].'">'.$row["title"].'</a><span class="board-post-cmt">'.$cmt_num.'</span></td>
                    <td class="board_author">'.$author.'</td>
                    <td class="board_date">'.$date.'</td>
                    <td class="board_views">'.$row["views"].'</td>
                    <td class="board_recommends">'.$row["recommends"].'</td>
                </tr>
                ';

            }
        ?>
    </tbody>
</table>
<div class="post-bottom-buttons">
    <button id="post-write-button-bottom-list" type="button" class="btn-mini bg-orange">글쓰기</button>
</div>
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
<script>
    $("#post-write-button-bottom-list")[0].addEventListener("click",function(){
        location.href="http://uraman.m-hosting.kr/ex_cms/board/write_post/?id=<? echo $_GET["id"]; ?>";
    });
</script>
