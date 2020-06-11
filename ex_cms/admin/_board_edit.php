<?php
    if(!isset($_POST["board_id"]))
        kick(0);

    $id = $_POST["board_id"];

    $sql = "SELECT * FROM CMS_board WHERE id='".$id."'";
    $result = sql_query($sql);
    $row = sql_get_row($result);

    $bid = $row["id"];
    $name = $row["name_kor"];
    $order_sub = $row["order_sub"];
    $gid = $row["group_id"];
    $access = $row["access"];
    $category = $row["category_list"];

    function display_on_menu($bool){
        global $order_sub;
        if($bool=="true" && !is_null($order_sub)){
            echo "checked";
        }
        else if($bool=="false" && is_null($order_sub)){
            echo "checked";
        }
    }

    function board_access($acc){
        global $access;
        if($acc == $access)
            echo "selected='selected'";
    }
 ?>

<style>
    #category_list>div{
        display:inline-block;
        box-sizing: border-box;
        height:30px;
        line-height: 28px;
        width:45%;
        border: 1px solid #ddd; border-radius: 5px;
        padding:0 3px;

        margin-right:1%; margin-bottom:5px;
        font-size: 12px;
    }

    #category_list span.name{color:#555;}

    #category_list a{
        display:inline-block;
        width:15px;
        height:15px;
        margin-left: 2px;
    }

    #category_list div.btns{
        height:100%;
        display:inline-block;
        float:right;
    }

    #category_list div.btns input{
        padding:0; margin-top:3px;
    }

    #category_list img{
        width:100%;
        height:100%;
    }


    #category_list div.edit input[type="text"]{
        width:70%; border:none; height:25px;
    }
    #category_list div.edit div.btns{
        width:30%;
    }
    #category_list div.edit input[type="button"]{
        width:100%;height:23px;
    }


    #board-info input[type="text"]{ height:27px; box-sizing: border-box; font-size: 13px}
    #board-info input[type="button"]{ height:27px; box-sizing: border-box; font-size: 13px}
    #board-info select{ height:27px; box-sizing: border-box; font-size: 13px}

</style>

<div id="admin-boards" style="display:flex; justify-content:center">
<form method="POST" action="process/_modify_board.php">
<div id="board-edit-box">
    <div id="board-info" style="width:450px">
        <table>
            <tr>
                <td class="label">게시판 주소</td>
                <td id="address" class="value">/ex_cms/board/?id=<?echo $bid?></td>
            </tr>
            <tr>
                <td class="label">게시판명</td>
                <td id="name" class="value">
                    <input type="text" name="name_kor" value="<?echo $name?>">
                </td>
            </tr>
            <tr>
                <td class="label">게시판그룹</td>
                <td id="group" class="value">
                    <select id="groups" name="group">
                        <?
                            $sql = "SELECT id,name_kor FROM CMS_board_group";
                            $result = sql_query($sql);
                            while($row = sql_get_row($result)){
                                $checked = "";
                                if($gid == $row["id"])
                                    $checked = "selected='selected'";
                                echo "<option value='".$row["id"]."' ".$checked."'>".$row["name_kor"]."</option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="label">게시글 분류</td>
                <td id="category" class="value">
                    <div class="head">
                        <input type="text" class="add_value" name="" value="">
                        <input type="button" name="" onclick="add_cat()" value="추가">
                    </div>
                </td>
            </tr>
            <tr style="height:0">
                <td class="label"></td>
                <td class="value">
                    <div id="category_list">
                    <?
                        $sql = "SELECT category_list FROM CMS_board WHERE id='".$bid."'";
                        $result = sql_query($sql);
                        if(sql_get_num_rows($result) == 0)
                            kick(0);

                        $cat_list = explode('|',sql_get_row($result)["category_list"]);

                        for($i=0; $i < count($cat_list); $i++){
                            echo
                            '<div>
                                <div class="edit invisible">
                                    <input type="text" class="editname" onkeydown="if(event.keyCode == 13)edit_done(this)" minlength="1" maxlength="7" value="'.$cat_list[$i].'">
                                    <div class="btns">
                                        <input type="button" onclick="edit_done(this)" value="확인">
                                    </div>
                                </div>
                                <div class="display">
                                    <span class="name">'.$cat_list[$i].'</span>
                                    <div class="btns">
                                        <a onclick="edit_cat(this)"><img src="/ex_cms/images/icon_edit.png"></a>
                                        <a onclick="remove_cat(this)"><img src="/ex_cms/images/icon_cancel.png"></a>
                                    </div>
                                    <input type="hidden" class="hdn" name="cat[]" value="'.$cat_list[$i].'">
                                </div>
                            </div>';
                        }
                    ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label">접근 제한</td>
                <td id="access" class="value">
                    <select class="" name="access">
                        <option value="guest" <?board_access("guest")?>>전체</option>
                        <option value="member" <?board_access("member")?>>회원 이상</option>
                        <option value="admin" <?board_access("admin")?>>관리자 이상</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="label">메뉴에 표시</td>
                <td id="order" class="value">
                    <input type="radio" name="display_on_menu" value="true" <? display_on_menu("true"); ?>>예
                    <input type="radio" name="display_on_menu" value="false"  <? display_on_menu("false"); ?>>아니오
                </td>
            </tr>
        </table>
        <input type="hidden" id="id" name="bid" value="<?echo $bid?>">
    </div>
    <div class="buttons" style="text-align:center">
        <input style="font-size:13.333px" id="done" type="submit" value="확인" class="btn-mini bg-orange">
        <input style="font-size:13.333px" type="button" value="취소" class="btn-mini bg-gray" onclick="location.href='?tab=boards'">
    </div>
</div>
</form>


<script>
    function remove_cat(tag){
        var cat = tag.closest("div#category_list>div");
        var cat_name = cat.querySelector("span.name").innerText;
        var bid = $("#id")[0].value;

        // $.ajax({
        //     url:"process/_ajax_remove_cat.php",
        //     method:"POST",
        //     data: {"bid":bid, "cat":cat_name},
        //     dataType: "text",
        //     success:function(data){
        //         console.log(data);
        //     }}
        // )

        cat.remove();
    }

    function edit_cat(tag){
        var cat = tag.closest("div#category_list>div");
        edit_cancel();
        shift_edit_display(cat,"edit");

        var cat_input = cat.querySelector("div.edit input");
        cat_input.focus();
        var tmp = cat_input.value;
        cat_input.value = "";
        cat_input.value = tmp;
    }

    function edit_cancel(){
        var cat_list = $("#category_list > div").toArray();
        for(i = 0; i < cat_list.length; i++){
            shift_edit_display(cat_list[i],"display");
        }
    }

    function shift_edit_display(cat,on){
        var off = "";
        if(on == "display")
            off="edit";
        if(on == "edit")
            off="display";

        if(on=="display"){
            cat.querySelector("input.editname").value = cat.querySelector("span.name").innerText;
        }

        if(cat.querySelector("div."+on+".invisible") != null)
            cat.querySelector("div."+on+".invisible").classList.remove("invisible");
        if(cat.querySelector("div."+off) != null)
            cat.querySelector("div."+off).classList.add("invisible");
    }

    function edit_done(tag){
        var cat = tag.closest("div#category_list>div");
        var new_cat_name = cat.querySelector("input.editname").value;
        cat.querySelector("span.name").innerText = new_cat_name;
        cat.querySelector("input.hdn").value = new_cat_name;
        shift_edit_display(cat,"display");
    }

    function add_cat(){
        var new_cat = $("#category input.add_value")[0].value;
        var new_element = $('<div>\
            <div class="edit invisible">\
                <input type="text" class="editname" onkeydown="if(event.keyCode == 13)edit_done(this)" minlength="1" maxlength="7" value="'+new_cat+'">\
                <div class="btns">\
                    <input type="button" onclick="edit_done(this)" value="확인">\
                </div>\
            </div>\
            <div class="display">\
                <span class="name">'+new_cat+'</span>\
                <div class="btns">\
                    <a onclick="edit_cat(this)"><img src="/ex_cms/images/icon_edit.png"></a>\
                    <a onclick="remove_cat(this)"><img src="/ex_cms/images/icon_cancel.png"></a>\
                </div>\
                <input type="hidden" class="hdn" name="cat[]" value="'+new_cat+'">\
            </div>\
        </div>')[0];
        var cat_list = $("#category_list")[0];
        cat_list.appendChild(new_element);
        $("#category input.add_value")[0].value = "";
    }


    // 엔터쳤을때 submit 방지
    $(document).on("keypress", 'form', function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            e.preventDefault();
            return false;
        }
    });
</script>
