<?php
    $result = sql_query("SELECT * FROM CMS_board_group ORDER BY order_nav");
?>

<style>
    #admin-boards {padding:30px 0; text-align: center}

    #board-order-box{ display:inline-block; width:200px; }

    #board-list {border:1px solid #bbb; text-align: left}
    #board-list ul {margin:0;}
    #board-list li{ font-size:14px; box-sizing: border-box;}
    #board-list .board{padding-left:20px}
    #board-list a {user-select: none; width:100%; height:100%; display:block; padding:5px 0; box-sizing: border-box;}
    #board-list .non-selectable {color:#999}

    #board-order-box .order-change-btns {text-align: center;}

    #admin-boards #buttons{text-align: center;margin-top:20px}
    a.on {background-color: #ddd;}
</style>

<div id="admin-boards">
<form id="order_done" method="POST" action="process/_board_order_change.php">
<div id="board-order-box">
    <div id="board-list">
        <ul class="list">
        <?
            while ($group = sql_get_row($result)){
                echo "<li><a class='group selectable'>".$group["name_kor"]."</a>";
                echo "<input type='hidden' name='group_order[]' value='".$group["id"]."'>";
                echo "<ul class='list'>";
                $boards = sql_query("SELECT * FROM CMS_board WHERE group_id=".$group["id"]." AND order_sub IS NOT NULL ORDER BY order_sub ASC");
                while ($board = sql_get_row($boards)){
                    echo "<li><a class='board selectable'>".$board["name_kor"]."</a>";
                    echo "<input type='hidden' name='board_order[]' value='".$group["id"].":".$board["id"]."'>";
                    echo "</li>";
                }
                $boards = sql_query("SELECT * FROM CMS_board WHERE group_id=".$group["id"]." AND order_sub IS NULL ORDER BY id ASC");
                while ($board = sql_get_row($boards)){
                    echo "<li><a class='board non-selectable'>(".$board["name_kor"].")</a>";
                    echo "</li>";
                }
                echo "</ul>";
                echo "</li>";
            }
        ?>
        </ul>
    </div>
    <div class="order-change-btns">
        <input type="button" class="up" value="▲" onclick="">
        <input type="button" class="down" value="▼">
    </div>
</div>
<div id="buttons">
    <input style="font-size:13.333px" type="submit" class="btn-mini bg-gray" name="submit_button" value="저장하기">
</div>
<input type="hidden" name="type" value="post">
</form>

<script>
    $("#board-list a.selectable").toArray().forEach((element) => {
        element.addEventListener("click",function(){
            $("#board-list a.selectable").toArray().forEach(element => reset_select(element));
            element.classList.add("on");
        });
    });

    $(".order-change-btns input").toArray().forEach((element) => {
        element.addEventListener("click",function(){
            var selected = $("#board-list .on")[0].closest("li");
            var direction = element.className;
            li_change_order(selected,direction);
        });
    });


    function reset_select(element){
        element.classList.remove("on");
    }

    // ul 내에서 li의 순서를 바꾸는 함수
    function li_change_order(li, direction){
        if(li.tagName != "LI"){
            console.log("li가 아님");
            return;
        }
        var parent = li.closest("ul");
        var order_now = get_li_index(li);

        if(direction == "up"){
            if(order_now == 0){
                console.log("이미 맨 위임");
                return;
            }
            parent.insertBefore(li, parent.children[order_now-1]);
        }
        else if(direction == "down"){
            if(order_now == parent.childElementCount - 1){
                console.log("이미 맨 아래임");
                return;
            }

            if(parent.children[order_now+1].querySelector("a").classList.contains("non-selectable")){
                console.log("아래로 이동불가");
                return;
            }

            parent.insertBefore(li, parent.children[order_now+2]);
        }
    }

    // ul 내에서 li의 순서를 반환하는 함수
    function get_li_index(li){
        if(li.tagName != "LI"){
            console.log("li가 아님");
            return;
        }
        var parent = li.closest("ul");
        for(var i = 0; i < parent.childElementCount; i++){
            if(li == parent.children[i])
                return i;
        }
        console.log("찾지 못함");
    }
</script>
