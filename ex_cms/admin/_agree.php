<?php
    $sql = "SELECT * FROM CMS_agreement ORDER BY CMS_agreement.order ASC";
    $result = sql_query($sql);
 ?>
<style>
    #agree{text-align: center;}
    #agree ul{
        box-sizing: border-box;
        width:600px;
        margin:30px auto;
        font-size: 14px;
        text-align: left;

        border-top:3px solid #555;
        border-bottom:2px solid #555;
    }
    #agree li{
        padding:20px 0;
        border-bottom: 1px solid #999;
    }
    #agree li>div{display:flex}
    #agree th{
        width:80px;
    }
    #agree td{
        width:500px;
    }
    #agree td textarea{
        box-sizing:border-box;
        padding:10px;
        width:100%;
        resize:vertical;
    }
    #agree input[type="text"]{
        height:27px;
        font-size: 13px;
        padding:0 10px;
    }

    #agree div.btns{
        display:flex;
        align-items: center;
        text-align: center;
        margin-left:10px;
    }
    #agree div.btns input{margin-bottom:5px}
</style>
<form id="action_form" action="" method="post">
<div id="agree">
    <ul>
        <?
        while($row=sql_get_row($result)){
            $id = $row["id"];
            $title = $row["agree_name"];
            $content = $row["agree_content"];
            $content_edit = str_replace("<br>","",$content);
            $is_rq = $row["is_required"];
            $OX = $is_rq?"O":"X";
            $checked_O = $is_rq?'checked':'';
            $checked_X = $is_rq?'':'checked';
        echo '<li>
            <div class="display">
                <table>
                    <tr>
                        <th>약관 제목</th>
                        <td>'.$title.'</td>
                    </tr>
                    <tr>
                        <th>약관 내용</th>
                        <td>
                            <div class="article-box">
                                <p class="article-text" style="font-size:13px">'.$content.'</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>필수 여부</th>
                        <td>'.$OX.'</td>
                    </tr>
                </table>
                <div class="btns">
                    <div>
                        <input type="button" class="up" onclick="move(this)" value="▲"><br>
                        <input type="button" class="btn-mini bg-gray" onclick="modify(this)" value="수정"><br>
                        <input type="button" class="btn-mini bg-gray" onclick="del(this)" value="삭제">
                        <input type="button" class="down" onclick="move(this)" value="▼">
                    </div>
                </div>
            </div>
            <div class="modify hidden-data">
                <table>
                    <tr>
                        <th>약관 제목</th>
                        <td> <input type="text" minlength="1" maxlength="100" required value="'.$title.'"> </td>
                    </tr>
                    <tr>
                        <th>약관 내용</th>
                        <td> <textarea name="name" rows="5" minlength="1" required>'.$content_edit.'</textarea> </td>
                    </tr>
                    <tr>
                        <th>필수 여부</th>
                        <td> <input type="radio" name="rq'.$id.'" required value="true" '.$checked_O.'>O <input type="radio" name="rq'.$id.'" required value="false" '.$checked_X.'>X </td>
                    </tr>
                </table>
                <div class="btns"><div>
                    <input type="button" class="btn-mini bg-orange btn-save" onclick="save(this)" value="저장"><br>
                    <input type="button" class="btn-mini bg-gray" onclick="cancel(this)" value="취소"></div>
                </div>
            </div>
            <input type="hidden" class="aid" value="'.$id.'">
        </li>';
        }
        ?>
        <div style="padding:20px; text-align:center">
            <button type="button" class="btn-mini bg-orange" style="width:150px; height:50px" onclick="add()">새 약관 추가</button>
        </div>
    </ul>
</div>
<div id="data">

</div>
</form>

<script>
    var tmp = $("#agree li")[0].cloneNode(true);
    tmp.classList.add("tmp");
    tmp.querySelector("div.display").remove();
    tmp.querySelector("div.modify").classList.remove("hidden-data");
    tmp.querySelector("input[type='text']").value="";
    tmp.querySelector("textarea").value="";
    tmp.querySelectorAll("input[type='radio']").forEach(function(element){element.checked="false"});
    tmp.querySelector("input.btn-mini.bg-orange").removeAttribute("onclick");

    function modify(element){
        var list_li = element.closest("li");
        reset();
        shift(list_li,true);
        list_li.querySelector("div.modify textarea").focus();
    }

    function reset(){
        var list = $("#agree ul")[0];
        for(i=0; i < list.childElementCount; i++){
            shift(list.children[i],false);
        }
    }

    function shift(list_li, is_modify){
        if(list_li.tagName != "LI")
            return;
        if(is_modify){
            if(!list_li.querySelector("div.display").classList.contains("hidden-data"))
                list_li.querySelector("div.display").classList.add("hidden-data");
            if(list_li.querySelector("div.modify").classList.contains("hidden-data"))
                list_li.querySelector("div.modify").classList.remove("hidden-data");
        }
        else{
            if(list_li.classList.contains("tmp"))
                {list_li.remove(); return;}
            if(list_li.querySelector("div.display").classList.contains("hidden-data"))
                list_li.querySelector("div.display").classList.remove("hidden-data");
            if(!list_li.querySelector("div.modify").classList.contains("hidden-data"))
                list_li.querySelector("div.modify").classList.add("hidden-data");
        }
    }

    function save(element){
        var list_li = element.closest("li");
        var id = list_li.querySelector("input.aid").value;

        write(list_li,id)
    }

    function cancel(element){
        var list_li = element.closest("li");

        shift(list_li,false);
    }

    function move(element){
        var list_li = element.closest("li");
        var parent = list_li.closest("ul");
        var order_now = get_li_index(list_li);
        var direction = element.className;
        var id = list_li.querySelector("input.aid").value;

        if(direction=="up"){
            if(order_now == 0){
                console.log("이미 맨 위임");
                return;
            }
            parent.insertBefore(list_li, parent.children[order_now-1]);
            agree_move(id,direction);
        }
        else if(direction=="down"){
            if(order_now == parent.childElementCount - 1){
                console.log("이미 맨 아래임");
                return;
            }
            if(parent.children[order_now+1].tagName=="DIV"){
                return;
            }
            if(parent.children[order_now+1].classList.contains("tmp")){
                return;
            }
            parent.insertBefore(list_li, parent.children[order_now+2]);
            agree_move(id,direction);
        }
    }

    function agree_move(id, direction){
        $.ajax({
            url:"process/_ajax_move_agreement.php",
            method:"POST",
            async:false,
            data: {"id":id, "direction":direction},
            dataType: "text",
            success:function(data){
                console.log(data);
            }}
        );
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

    function del(element){
        if(confirm("이 약관을 삭제하시겠습니까?")){
            var list_li = element.closest("li");
            var id = list_li.querySelector("input.aid").value;
            set_post("id",id);
            set_action_submit("_delete_agreement.php");
        }
    }

    function add(){
        reset();
        var _new = tmp.cloneNode(true);
        _new.querySelector(".btn-save").addEventListener("click",function(){write(_new)});
        $("#agree ul")[0].insertBefore(_new,$("#agree ul>div")[0]);
    }

    // id가 null이면 신규생성, null이 아니면 수정
    function write(li, id=null){
        var rq;
        var rq_o = li.querySelectorAll("input[type='radio']")[0];
        var rq_x = li.querySelectorAll("input[type='radio']")[1];
        if(!rq_o.checked && !rq_x.checked){
            alert("필수 여부를 선택해주세요.");
            rq_o.focus();
            return;
        }
        else if(rq_o.checked)
            rq=true;
        else if(rq_x.checked)
            rq=false;
        var title = li.querySelector("input[type='text']").value;
        var content = li.querySelector("textarea").value;

        if(title.length > 100 || title.length < 1){
            alert("약관 제목을 1~100자로 입력해주세요.");
            return;
        }
        if(content.length < 1){
            alert("약관 내용을 입력해주세요.");
            return;
        }
        
        set_post("required",rq);
        set_post("id",id);
        set_post("title",title);
        set_post("content",content);

        set_action_submit("_write_agreement.php");
    }

    function set_post(name,value){
        var element = $("<input type='hidden' name='"+name+"' value='"+value+"'>")[0];
        $("#data")[0].append(element);
    }

    function set_action_submit(action, proc=true){
        if(proc)
            $("#action_form")[0].action = "process/" + action;
        else {
            $("#action_form")[0].action = action;
        }
        $("#action_form")[0].submit();
    }
</script>
