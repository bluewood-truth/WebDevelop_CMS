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
        height:22px;
        line-height: 22px;
        width:45%;
        border: 1px solid #ddd; border-radius: 5px;
        padding:3px;
        margin-right:1%; margin-bottom:5px;
        font-size: 13px;
    }
    #category_list a{
        display:inline-block;
        width:15px;
        height:15px;
        margin-left: 2px;
    }

    #category_list div.btns{
        display:inline-block;
        float:right;
    }

    #category_list img{
        width:100%;
        height:100%;
    }

</style>

<div id="admin-boards" style="display:flex; justify-content:center">
<form id="order_done" method="POST" action="process/">
<div id="board-edit-box">
    <div id="board-info" style="width:400px">
        <table>
            <tr>
                <td class="label">게시판 주소</td>
                <td id="address" class="value">/ex_cms/board/?id=<?echo $bid?></td>
            </tr>
            <tr>
                <td class="label">게시판명</td>
                <td id="name" class="value">
                    <input type="text" value="<?echo $name?>">
                </td>
            </tr>
            <tr>
                <td class="label">게시판그룹</td>
                <td id="group" class="value">
                    <select id="groups" name="groups">
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
                        <input type="text" name="" value="">
                        <input type="button" name="" value="확인">
                    </div>
                </td>
            </tr>
            <tr style="height:0">
                <td class="label"></td>
                <td class="value">
                    <div id="category_list">
                        <div>
                            <span class="name">애플</span>
                            <div class="btns">
                                <a href="#"><img src="/ex_cms/images/icon_edit.png"></a>
                                <a href="#"><img src="/ex_cms/images/icon_cancel.png"></a>
                            </div>
                            </div>

                            <input type="hidden" name="cat[]">
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label">접근 제한</td>
                <td id="access" class="value">
                    <select class="" name="">
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
        <input type="hidden" id="id" name="board_id" value="">
        <div class="buttons">
            <input style="font-size:13.333px" id="done" type="submit" value="확인" class="btn-mini bg-orange" onclick="">
            <input style="font-size:13.333px" type="button" value="취소" class="btn-mini bg-gray" onclick="location.href='?tab=boards'">
        </div>
    </div>
</div>
</form>
