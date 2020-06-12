<form id="agreement_form" action="http://uraman.m-hosting.kr/ex_cms/join/join.php?page=form" method="post">
<ul id="join-elements-list">
    <?
        $table = sql_query("SELECT * FROM CMS_agreement");
        while(!empty($row = sql_get_row($table))){
            $required_text = $row["is_required"]?"필수":"선택";
            $required_class = $row["is_required"]?"required":"optional";
            $aid = 'aid'.$row['id'];
            echo '<li class="join-element padding-bottom">
                    <div class="join-label-box">
                        <input id="'.$aid.'" name="'.$aid.'" type="checkbox" class="chk">
                        <label for="'.$aid.'" class="join-label">'.$row['agree_name'].'<span class="'.$required_class.'">('.$required_text.')</span></label>
                    </div>';
            if($row['agree_content'] != ""){
                echo '<div class="article-box"><p class="article-text">'.$row['agree_content'].'</p></div>';
            }
            echo    '</li>';
        }
        echo '<li class="join-element">
            <div class="agreement-label-box">
                <input id="all" name="all" type="checkbox" class="chk">
                <label for="all" class="join-label" style="text-decoration:underline">(선택 항목 포함) 전체 이용약관에 동의합니다.</label>
            </div></li>';
    ?>
</ul>
<input type="button" class="custom-button short bg-gray" onclick="location.href=document.referrer" value="취소">
<input type="button" class="custom-button short bg-orange" id="btn_ok" value="확인">
</form>

<script>
    var chk_boxes = $('input.chk');
    for(var key = 0; key < chk_boxes.length; key++){
        (function(){
            var chkbox = chk_boxes[key];
            if(chkbox.id=="all"){
                chkbox.addEventListener("change",checkbox_all);
            }
            else{
                chkbox.addEventListener("change",function(){
                    chkbox_change_event(chkbox);
                });
            }
        }());
    }
    $("#btn_ok")[0].addEventListener("click",button_ok_event);

    function checkbox_all(){
        var chkbox = $('#all')[0];
        var tf = chkbox.checked;

        var chk_boxes = $('input.chk');
        for(var key = 0; key < chk_boxes.length; key++){
            var chkbox = chk_boxes[key];
            if(chkbox.id=="all"){
                continue;
            } else{
                chkbox.checked = tf;
            }
        }
    }

    function chkbox_change_event(chkbox){
        if(chkbox.checked == false){
            $('#all')[0].checked = false;
        }
        else{
            var all_checked = true;
            var chk_boxes = $('input.chk');
            for(var key = 0; key < chk_boxes.length; key++){
                if(chk_boxes[key].id == "all"){
                    continue;
                }
                if(chk_boxes[key].checked == false){
                    all_checked = false;
                    break;
                }
            }
            if(all_checked == true){
                $('#all')[0].checked = true;
            }
        }
    }

    function button_ok_event(){
        var required_checked = true;
        var chk_boxes = $('input.chk');
        for(var key = 0; key < chk_boxes.length; key++){
            var chkbox = chk_boxes[key];
            if(chkbox.class == "required" && chkbox.checked == false){
                required_checked = false;
            }
        }
        if(required_checked == false){
            alert("모든 필수약관에 동의해주세요.");
            return;
        }

        set_post("agreement_form","aggrement","aggrement");
        $("#agreement_form").submit();
    }

</script>
