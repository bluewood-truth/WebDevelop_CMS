<?php
    $result = sql_query("SELECT * FROM CMS_board_group ORDER BY order_nav");
?>

<form id="action_form" method="POST" >
<div id="admin-boards">
    <div id="board-order-box" style="margin-right:30px;">
        <div class="order-change-btn">
            <input style="font-size:13.333px" type="button" value="+ 게시판" class="btn-mini bg-orange" onclick="location.href='?tab=board_create'">
            <input style="font-size:13.333px;" type="button" value="+ 게시판그룹" class="btn-mini bg-orange" onclick="location.href='?tab=group_create'">
        </div>
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
        <div class="order-change-btn">
            <input style="font-size:13.333px" type="button" value="순서 변경" class="btn-mini bg-gray" onclick="location.href='?tab=board_order'">
        </div>
    </div>
    <div id="group-info" style="display:none">
        <table>
            <tr>
                <td class="label">게시판그룹명</td>
                <td id="name" class="value">
                    <span></span>
                    <div id="modify-groupname-box" style="display:none">
                        <input class="text" minlength="1" maxlength="7" type="text" name="group_name" value="">
                        <input class="submit" type="submit" name="" value="확인">
                    </div>
                </td>
            </tr>
        </table>
        <input type="hidden" id="id" name="group_id" value="">
        <div class="buttons">
            <input style="font-size:13.333px" id="modify-groupname" type="button" value="게시판그룹명 수정" class="btn-mini bg-gray" onclick="">
            <input style="font-size:13.333px" id="delete-group" type="submit" value="게시판그룹 삭제" class="btn-mini bg-gray" onclick="">
        </div>
    </div>
    <div id="board-info" style="display:none">
        <table>
            <tr>
                <td class="label">게시판 주소</td>
                <td id="address" class="value"></td>
            </tr>
            <tr>
                <td class="label">게시판명</td>
                <td id="name" class="value"></td>
            </tr>
            <tr>
                <td class="label">게시판그룹</td>
                <td id="group" class="value"></td>
            </tr>
            <tr>
                <td class="label">게시글 분류</td>
                <td id="category" class="value"></td>
            </tr>
            <tr>
                <td class="label">접근 제한</td>
                <td id="access" class="value"></td>
            </tr>
            <tr>
                <td class="label">메뉴에 표시</td>
                <td id="order" class="value"></td>
            </tr>
        </table>
        <input type="hidden" id="id" name="board_id" value="">
        <div class="buttons">
            <input style="font-size:13.333px" type="submit" value="게시판 수정" class="btn-mini bg-gray" onclick='set_action_submit("?tab=board_edit",false);'>
            <input style="font-size:13.333px" id="clear-board" type="submit" value="게시판 초기화" class="btn-mini bg-gray" onclick="">
            <input style="font-size:13.333px" id="delete-board" type="submit" value="게시판 삭제" class="btn-mini bg-gray" onclick="">
        </div>
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

    function get_info(element){
        var type;
        if(element.classList.contains("board"))
            type = "board";
        else if (element.classList.contains("group"))
            type = "group";

        var id = element.closest("li").querySelector("input.id").value;

        var info;
        $.ajax({
            url:"process/_ajax_get_info.php",
            method:"POST",
            async:false,
            data: {"type":type, "id":id},
            dataType: "json",
            success:function(data){
                info = data[0];
            }}
        );

        show_info_container(type);
        update_info(info,type);
    }

    function show_info_container(type){
        if(type=="board"){
            $("#board-info")[0].style.display="";
            $("#group-info")[0].style.display="none";
        }
        else if (type=="group"){
            $("#board-info")[0].style.display="none";
            $("#group-info")[0].style.display="";
        }
        else{
            $("#board-info")[0].style.display="";
            $("#group-info")[0].style.display="";
        }
    }

    function update_info(data,type){
        if(type=="board"){
            var category = "";
            if(data.category_list == null){
                category = "-";
            }
            else{
                var arr = data.category_list.split("|");
                for(i = 0; i < arr.length; i++){
                    category += "["+arr[i]+"] ";
                }
            }
            $("#board-info #address")[0].innerText = "/ex_cms/board/?id="+data.id;
            $("#board-info #name")[0].innerText = data.name_kor;
            $("#board-info #group")[0].innerText = get_group($("#board-list a.board.selectable.on")[0]);
            $("#board-info #category")[0].innerText = category;
            $("#board-info #access")[0].innerText = access_translation(data.access);
            $("#board-info #order")[0].innerText = data.order_sub == null ? "X" : "O";
            $("#board-info #id")[0].value = data.id;
        }
        else if (type=="group"){
            $("#modify-groupname-box")[0].style.display="none";
            $("#group-info #name span")[0].style.display="";

            $("#group-info #name span")[0].innerText = data.name_kor;
            $("#group-info #name #modify-groupname-box input.text")[0].value = data.name_kor;
            $("#group-info #id")[0].value = data.id;
        }
        else{
            show_info_container();
        }
    }

    function access_translation(access){
        switch(access){
            case "guest":
                return "전체";
            case "member":
                return "회원 이상";
            case "admin":
                return "관리자  이상"
        }
    }

    function get_group(element){
        return element.closest("div#board-list>ul.list>li").querySelector("a").innerText;
    }



    function set_action_submit(action, proc=true){
        if(proc)
            $("#action_form")[0].action = "process/" + action;
        else {
            $("#action_form")[0].action = action;
        }
        $("#action_form")[0].submit;
    }

    // ======================================================================================
    // 게시판그룹명 수정
    $("#modify-groupname")[0].addEventListener("click",function(){
        $("#modify-groupname-box")[0].style.display="";
        $("#group-info #name span")[0].style.display="none";
    });
    $("#modify-groupname-box input.submit")[0].addEventListener("click",function(event){
        var reg = /^[가-힣a-zA-Z0-9 ]{1,7}$/;
        if(reg.test($("#modify-groupname-box input.text")[0].value)){
            set_action_submit("_modify_group_name.php");
        }
        else{
            alert("1~7자의 한글, 영문, 숫자, 공백만 입력 가능합니다.")
            event.preventDefault();
        }
    });
    // =====================================================================================

    // =====================================================================================
    // 게시판 초기화
    $("#clear-board")[0].addEventListener("click",function(event){
        if(confirm("정말로 게시판의 모든 게시글을 삭제하시겠습니까?\n(이 동작은 취소 및 복구가 불가능합니다.)")){
            set_action_submit("_clear_board.php");
        }
        else{
            event.preventDefault();
        }
    });
    // =====================================================================================

    // =====================================================================================
    // 게시판그룹 삭제
    $("#delete-group")[0].addEventListener("click",function(event){
        if(confirm("게시판그룹을 삭제하시겠습니까?")){
            set_action_submit("_delete_group.php");
        }
        else{
            event.preventDefault();
        }
    });
    // =====================================================================================

    // =====================================================================================
    // 게시판 삭제
    $("#delete-board")[0].addEventListener("click",function(event){
        if(confirm("정말로 게시판을 삭제하시겠습니까?\n(게시판 내 모든 게시글은 삭제되며, 이 동작은 취소 및 복구가 불가능합니다.)")){
            set_action_submit("_delete_board.php");
        }
        else{
            event.preventDefault();
        }
    });

    // =====================================================================================

</script>
