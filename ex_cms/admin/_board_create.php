<?php

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


    #board-edit-box #board-info input[type="text"]{ height:27px; box-sizing: border-box; font-size: 13px}
    #board-edit-box #board-info input[type="button"]{ height:27px; box-sizing: border-box; font-size: 13px}
    #board-edit-box #board-info select{ height:27px; box-sizing: border-box; font-size: 13px}

</style>

<div id="admin-boards" style="display:flex; justify-content:center">
<form id="create_board_form" method="POST" action="process/_create_board.php">
<div id="board-edit-box">
    <div id="board-info" style="width:450px">
        <table>
            <tr>
                <td class="label">게시판 주소</td>
                <td id="address" class="value">
                    /ex_cms/board/?id=<input type="text" style="width:150px" name="bid" minlength="1" maxlength="16" required="required" value="">
                </td>
            </tr>
            <tr>
                <td class="label">게시판명</td>
                <td id="name" class="value">
                    <input type="text" name="name_kor" minlength="1" maxlength="7" required="required" value="">
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
                        <input type="text" class="add_value" name="" onkeydown="if(event.keyCode == 13)add_cat()"  minlength="1" maxlength="7" value="">
                        <input type="button" name="" onclick="add_cat()" value="추가">
                    </div>
                </td>
            </tr>
            <tr style="height:0">
                <td class="label"></td>
                <td class="value">
                    <div id="category_list">

                    </div>
                </td>
            </tr>
            <tr>
                <td class="label">접근 제한</td>
                <td id="access" class="value">
                    <select class="" name="access">
                        <option value="guest">전체</option>
                        <option value="member">회원 이상</option>
                        <option value="admin">관리자 이상</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="label">메뉴에 표시</td>
                <td id="order" class="value">
                    <input type="radio" name="display_on_menu" required value="true">예
                    <input type="radio" name="display_on_menu" required value="false">아니오
                </td>
            </tr>
        </table>
    </div>
    <div class="buttons" style="text-align:center">
        <input style="font-size:13.333px" id="done" type="submit" value="확인" class="btn-mini bg-orange">
        <input style="font-size:13.333px" type="button" value="취소" class="btn-mini bg-gray" onclick="location.href='?tab=boards'">
    </div>
</div>
</form>

<script type="text/javascript" src="_js_board_category_handler.js"></script>
<script>

    // 유효성검사
    var check_address = /^[a-z0-9_]+$/;
    $("#create_board_form")[0].addEventListener("submit",function(event){
        var board_address = $("#address>input")[0].value;
        var board_name = $("#name>input")[0].value;
        if(!check_address.test(board_address)){
            alert("게시판 주소는 영문소문자, 숫자, 언더바(_)로 이루어져야 합니다.");
            $("#address>input")[0].focus();
            event.preventDefault();
        }else if(duplicate_check(board_address,"CMS_board","id")){
            alert("이미 존재하는 주소입니다.");
            $("#address>input")[0].focus();
            event.preventDefault();
        }else if(duplicate_check(board_name,"CMS_board","name_kor")){
            alert("이미 존재하는 게시판명입니다.");
            $("#name>input")[0].focus();
            event.preventDefault();
        }
    });
</script>
