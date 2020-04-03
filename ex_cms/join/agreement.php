<ul id="join-elements-list">
    <?
        $table = sql_query("SELECT * FROM CMS_agreement");
        while(!empty($row = sql_get_row($table))){
            $required_text = $row["is_required"]?"필수":"선택";
            $required_class = $row["is_required"]?"required":"optional";
            $aid = 'aid'.$row['id'];
            echo '<li class="join-element">
                    <div class="agreement-label-box">
                        <input id="'.$aid.'" name="'.$aid.'" type="checkbox" class="chk">
                        <label for="'.$aid.'" class="agreement-label">'.$row['agree_name'].'<span class="'.$required_class.'">('.$required_text.')</span></label>
                    </div>';
            if($row['agree_content'] != ""){
                echo '<div class="article-box"><p class="article-text">'.$row['agree_content'].'</p></div>';
            }
            echo    '</li>';
        }
        echo '<li class="join-element">
            <div class="agreement-label-box">
                <input id="all" name="all" type="checkbox" class="chk">
                <label for="all" class="agreement-label" style="text-decoration:underline">(선택 항목 포함) 전체 이용약관에 동의합니다.</label>
            </div></li>';
    ?>
</ul>
<input type="button" class="short-button-gray" onclick="location.href=document.referrer"value="취소">
<input type="button" class="short-button" value="확인">

<script>
    var chk_boxes = $('input.chk');
    for(var key in chk_boxes){
        var chkbox = chk_boxes[key];
        if(chkbox.id=="all"){
            chkbox.addEventListener("change",checkbox_all);
        }
    }

    function checkbox_all(){
        var chkbox = $('#all')[0];
        var tf = chkbox.checked?true:false;

        var chk_boxes = $('input.chk');
        for (var key in chk_boxes){
            var chkbox = chk_boxes[key];
            if(chkbox.id=="all"){
                continue;
            } else{
                chkbox.checked = tf;
            }
        }

    }
</script>
