<?php
    $result = sql_query("SELECT * FROM CMS_board_group ORDER BY order_nav");
?>

<form id="action_form" method="POST" >
<div id="admin-boards">
    <div id="board-order-box" style="margin-right:30px;">
        <div id="board-list">
            <ul class="list">
            <?
                while ($group = sql_get_row($result)){
                    echo "<li><a class='group selectable'>".$group["name_kor"]."</a>";
                    echo "<input class='id' type='hidden' value='".$group["id"]."'>";
                    echo "<ul class='list'>";
                    $boards = sql_query("SELECT * FROM CMS_board WHERE group_id=".$group["id"]." AND order_sub IS NOT NULL ORDER BY order_sub ASC");
                    while ($board = sql_get_row($boards)){
                        echo "<li><a class='board selectable'>".$board["name_kor"]."</a>";
                        echo "<input class='id' type='hidden' value='".$board["id"]."'>";
                        echo "</li>";
                    }
                    $boards = sql_query("SELECT * FROM CMS_board WHERE group_id=".$group["id"]." AND order_sub IS NULL ORDER BY id ASC");
                    while ($board = sql_get_row($boards)){
                        echo "<li><a class='board selectable'>(".$board["name_kor"].")</a>";
                        echo "<input class='id' type='hidden' value='".$board["id"]."'>";
                        echo "</li>";
                    }
                    echo "</ul>";
                    echo "</li>";
                }
            ?>
            </ul>
        </div>
    </div>
    <div>
        
    </div>
    <div>

    </div>
</div>
</form>


<script>
    $("#board-list a.selectable").toArray().forEach((element) => {
        element.addEventListener("click",function(){
            $("#board-list a.selectable").toArray().forEach(element => reset_select(element));
            element.classList.add("on");
            get_info(element);
        });
    });

    function reset_select(element){
        element.classList.remove("on");
    }
</script>
