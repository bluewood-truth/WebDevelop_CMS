<style>
    .displayed{color:#dbaf00}
</style>

<form id="action_form" method="POST" action="process/_display_board.php">
<div id="admin-boards" style="display:flex; justify-content:center">
    <div id="board-order-box">
        <div id="board-list">
            <ul class="list">
            <?
                $result = sql_query("SELECT * FROM CMS_board_group ORDER BY order_nav");
                while ($group = sql_get_row($result)){
                    echo "<li><a class='group'>".$group["name_kor"]."</a>";
                    echo "<input class='id' type='hidden' value='".$group["id"]."'>";
                    echo "<ul class='list'>";
                    $boards = sql_query("SELECT * FROM CMS_board WHERE group_id=".$group["id"]." AND order_sub IS NOT NULL ORDER BY order_sub ASC");
                    while ($board = sql_get_row($boards)){
                        $dsp = "";
                        if(!is_null($board["display_on_main"]))
                            $dsp = " displayed";
                        echo "<li id='bid".$board["id"]."'><a class='board".$dsp." selectable'>".$board["name_kor"]."</a>";
                        echo "<input class='id' type='hidden' value='".$board["id"]."'>";
                        echo "</li>";
                    }
                    $boards = sql_query("SELECT * FROM CMS_board WHERE group_id=".$group["id"]." AND order_sub IS NULL ORDER BY id ASC");
                    while ($board = sql_get_row($boards)){
                        $dsp = "";
                        if(!is_null($board["display_on_main"]))
                            $dsp = " displayed";
                        echo "<li id='bid".$board["id"]."'><a class='board".$dsp." selectable'>(".$board["name_kor"].")</a>";
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
    <div style="display: flex; align-items: center; padding:0 10px;">
        <div class="order-add-btns">
            <input type="button" class="in" value="▶">
            <br>
            <input type="button" class="out" value="◀">
        </div>
    </div>
    <div>
        <div id="main-list" style="min-height:200px">
            <ul class="list">
            <?
                $result = sql_query("SELECT * FROM CMS_board WHERE display_on_main IS NOT NULL ORDER BY display_on_main");
                while($board=sql_get_row($result)){
                    echo "<li id='mbid".$board["id"]."'><a class='board selectable' onclick='select_li(this)'>".$board["name_kor"]."</a>";
                    echo "<input class='id' type='hidden' name='display[]' value='".$board["id"]."'>";
                    echo "</li>";
                }
            ?>
            </ul>
        </div>
        <div class="order-change-btns" style="text-align:center">
            <input type="button" class="up" value="▲">
            <input type="button" class="down" value="▼">
        </div>
    </div>
</div>
<div id="buttons" style="text-align:center; margin-bottom:30px;">
    <input style="font-size:13.333px" type="submit" class="btn-mini bg-orange" name="submit_button" value="저장하기">
</div>
</form>


<script>
    $("#board-list a.selectable").toArray().forEach((element) => {
        element.addEventListener("click",function(){
            $("#admin-boards a.selectable").toArray().forEach(element => reset_select(element));
            element.classList.add("on");
        });
    });

    function select_li(element){
        $("#admin-boards a.selectable").toArray().forEach(element => reset_select(element));
        element.classList.add("on");
    }

    function reset_select(element){
        element.classList.remove("on");
    }

    $(".order-change-btns input").toArray().forEach((element) => {
        element.addEventListener("click",function(){
            var selected = $("#main-list .on")[0].closest("li");
            var direction = element.className;
            li_change_order(selected,direction);
        });
    });

    $(".order-add-btns input").toArray().forEach((element) => {
        element.addEventListener("click",function(){
            var direction = element.className;
            var id;
            if(direction=="in"){
                var id_element = $("#board-list .on")[0].closest("li").querySelector("input.id");
                if(!id_element)
                    return;
                var id = id_element.value.replace("bid","");
            }
            else if(direction=="out"){
                var id_element = $("#main-list .on")[0].closest("li").querySelector("input.id");
                if(!id_element)
                    return;
                var id = id_element.value.replace("mbid","");
            }
            li_add_remove(id,direction);
        });
    });

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

    function li_add_remove(id, direction){
        if(direction=="in")
            li_in(id);
        else if(direction=="out")
            li_out(id);
    }

    function li_in(id){
        if($("#main-list #mbid"+id)[0])
            return;

        var name;
        $.ajax({
            url:"process/_ajax_get_boardname.php",
            method:"POST",
            async:false,
            data: {"id":id},
            dataType: "text",
            success:function(data){
                name=data;
            }}
        );

        var element = $("<li id='mbid"+id+"'><a class='board selectable' onclick='select_li(this)'>"+name+"</a>\
                    <input class='id' type='hidden' name='display[]' value='"+id+"'>\
                    </li>")[0];

        $("#main-list ul.list")[0].append(element);
        $("#board-list #bid"+id)[0].querySelector("a").classList.add("displayed");
    }

    function li_out(id){
        if(!$("#main-list #mbid"+id)[0])
            return;

        var child = $("#main-list #mbid"+id)[0];
        var main = $("#main-list ul.list")[0];
        main.removeChild(child);
        $("#board-list #bid"+id)[0].querySelector("a").classList.remove("displayed");
    }
</script>
