<?php
    if(isset($_GET["id"]) == false){
        invalid_access();
    }
    $table = sql_query("SELECT * from CMS_post_".$_GET['id']." ORDER BY id DESC");

    $result = sql_query("SELECT category_list FROM CMS_board WHERE 'id'='".$_GET['id']."'");
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
                $author = $row["author_nickname"];
                if(is_null($author)){
                    $author = $row["guest_name"];
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
                echo '<td class="board_title">'.$row["title"].'</td>
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
