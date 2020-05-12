<?php
    $table = sql_query("SELECT * from CMS_post_".$_GET['id']." ORDER BY id DESC");

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

                echo '
                <tr>
                    <td class="board_num">'.$row["id"].'</td>';
                if($is_categorical) echo '<td class="board_cat">'.$row["category"].'</td>';
                echo '<td class="board_title"><a href="http://uraman.m-hosting.kr/ex_cms/board/?id='.$_GET["id"].'&post='.$row["id"].'">'.$row["title"].'</a></td>
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

<script>
    $("#post-write-button-bottom-list")[0].addEventListener("click",function(){
        location.href="http://uraman.m-hosting.kr/ex_cms/board/write_post/?id="+"<? echo $_GET["id"]; ?>";
    });
</script>
