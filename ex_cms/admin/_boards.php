<?php
    $sql = "SELECT * FROM CMS_board ORDER BY group_id";
    $boards = sql_query($sql);

    $result = sql_query("SELECT id,name_kor FROM CMS_board_group");
    $groups;
    while($row = sql_get_row($result)){
        $groups[$row["id"]] = $row["name_kor"];
    }
?>

<style>
    #mypage-posts{padding:30px 0;}
    #mypage-posts table{font-size:13px;  border-collapse: collapse;
        border-top:2px solid #aaa; border-bottom:2px solid #aaa; margin-top:10px}
    #mypage-posts tr{height:30px}
    #mypage-posts thead {border-bottom:2px solid #aaa;}
    #mypage-posts tbody tr{border-bottom:1px solid #aaa;}
    #mypage-posts .group{width:120px; text-align: center;}
    #mypage-posts .board{width:120px; text-align: center;}
    #mypage-posts .board a:hover{text-decoration: underline;}
    #mypage-posts .access{width:120px; text-align: center;}
    #mypage-posts #buttons{text-align: right; margin:5px;}
    #mypage-posts .msg{font-weight: bold; font-size:13px;}
</style>

<div id="mypage-posts">
<form id="delete_post_form" method="POST" action="process/_delete_process.php">
<table>
    <thead>
        <tr>
            <th class="group">게시판그룹명</th>
            <th class="board">게시판명</th>
            <th class="access">접근제한</th>
        </tr>
    </thead>
    <tbody>
    <?
        while ($row = sql_get_row($boards)){
            echo "
            <tr>
                <td class='group'>".$groups[$row["group_id"]]."</th>
                <td class='board'>".$row["name_kor"]."</th>
                <td class='access'>".authority_kor($row["access"]   )."</th>
            </tr>
            ";
        }
    ?>
    </tbody>
</table>
<div id="buttons">
    <input id="select_delete" style="font-size:13.333px" type="submit" class="btn-mini bg-gray" name="submit_button" value="선택 삭제">
    <input id="all_delete" style="font-size:13.333px" type="submit" class="btn-mini bg-gray" onclick="return confirm('정말로 모든 게시글을 삭제하시겠습니까?')" name="submit_button" value="전체 삭제">
</div>
<input type="hidden" name="type" value="post">
</form>
</div>

<script>
</script>
