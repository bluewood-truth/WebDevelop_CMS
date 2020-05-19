<?php
    $sql = "SELECT id,name_kor,category_list FROM CMS_board";
    $result = sql_query($sql);

    $sql = "";
    while($row = sql_get_row($result)){
        if($sql == ""){
            $sql = "SELECT id,category,title,write_date,views,recommends,'".$row["name_kor"]."' as name_kor,'".$row["category_list"]."' as categorical,'".$row["id"]."' as bid FROM CMS_post_".$row["id"]." WHERE author_id=".$_SESSION["login"];
        }
        else{
            $sql = $sql." UNION ALL SELECT id, category,title,write_date,views,recommends,'".$row["name_kor"]."' as name_kor,'".$row["category_list"]."' as categorical,'".$row["id"]."' as bid FROM CMS_post_".$row["id"]." WHERE author_id=".$_SESSION["login"];
        }
    }
    $page = 1;
    if(isset($_GET["page"]))
        $page = $_GET["page"];
    $post_by_page = 10;
    $page_interval = 10;

    $total_post = sql_get_num_rows(sql_query($sql));
    $total_page = intval(($total_post-1) / $post_by_page) + 1;

    $posts = sql_query($sql." ORDER BY write_date DESC LIMIT ".(($page-1) * $post_by_page).",".$post_by_page);
?>

<style>
    #mypage-posts{padding:30px 0;}
    #mypage-posts table{font-size:13px; width:100%; border-collapse: collapse;
        border-top:2px solid #aaa; border-bottom:2px solid #aaa; }
    #mypage-posts tr{height:30px}
    #mypage-posts thead {border-bottom:2px solid #aaa;}
    #mypage-posts tbody tr{border-bottom:1px solid #aaa;}
    #mypage-posts .chkbox{width:30px; text-align: center;}
    #mypage-posts .board{width:120px; text-align: center;}
    #mypage-posts .title a:hover{text-decoration: underline;}
    #mypage-posts .date{width:80px; text-align: center;}
    #mypage-posts .views{width:40px; text-align: center;}
    #mypage-posts .recommends{width:40px; text-align: center;}
    #mypage-posts #buttons{text-align: right; margin:5px;}
</style>

<div id="mypage-posts">
<table>
    <thead>
        <tr>
            <th class="chkbox"><input type="checkbox" class="checkbox" onchange="check_all(this)" id="all"></th>
            <th class="board">게시판</th>
            <th class="title">제목</th>
            <th class="date">작성일</th>
            <th class="views">조회</th>
            <th class="recommends">추천</th>
        </tr>
    </thead>
    <tbody>
    <?
        while ($row = sql_get_row($posts)){
            $cat = "";
            if(!empty($row["categorical"]))
                $cat = "<span class='cat'>[".$row["category"]."] </span>";

            $date_tmp = date_create($row["write_date"]);
            $date = date_format($date_tmp,"Y-m-d");
            if($date == date("Y-m-d")){
                $date = date_format($date_tmp,"H:i");
            }
            echo "
            <tr>
                <td class='chkbox'><input type='checkbox' class='checkbox' id='".$row["bid"]."_".$row["id"]."'></td>
                <td class='board'>".$row["name_kor"]."</td>
                <td class='title'><a href='http://uraman.m-hosting.kr/ex_cms/board/?id=".$row["bid"]."&pid=".$row["id"]."'>".$cat.$row["title"]."</a></td>
                <td class='date'>".$date."</td>
                <td class='views'>".$row["views"]."</td>
                <td class='recommends'>".$row["recommends"]."</td>
            </tr>
            ";
        }
    ?>
    </tbody>
</table>
<div id="buttons">
    <button id="select_delete" type="button" class="btn-mini bg-gray">선택 삭제</button>
    <button id="all_delete" type="button" class="btn-mini bg-gray">전체 삭제</button>
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

    $("#buttons #select_delete")[0].addEventListener("click",function(){
        if(confirm("삭제하시겠습니까?")){
            var post_data = [];
            for(i = 0; i < chkbox.length; i++){
                if(chkbox[i].id == "all")
                    continue;
                if(chkbox[i].checked)
                post_data.push(chkbox[i].id);
            }
        }
    });

</script>
