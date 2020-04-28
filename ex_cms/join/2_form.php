<?php
    if(isset($_POST["aggrement"]) == false){
        invalid_access();
    }
 ?>
<form action="" method="post">
<ul id="join-elements-list">
    <li class="join-element padding-bottom-mini">
        <p class="join-label-box"><label class="join-label" for="input_id">아이디</label></P>
        <div class="join-input-box">
            <input class="join-input width-full" maxlength="20" type="text" id="input_id" name="input_id">
        </div>
        <p class="join-message"></p>
    </li>
    <li class="join-element padding-bottom-mini">
        <p class="join-label-box"><label class="join-label" for="input_pw">비밀번호</label></P>
        <div class="join-input-box">
            <input class="join-input width-full" maxlength="20" type="password" id="input_pw" name="input_pw">
        </div>
        <p class="join-message"></p>
    </li>
    <li class="join-element padding-bottom-mini">
        <p class="join-label-box"><label class="join-label" for="input_pwc">비밀번호 확인</label></P>
        <div class="join-input-box">
            <input class="join-input width-full" maxlength="20" type="password" id="input_pwc" name="input_pwc">
        </div>
        <p class="join-message"></p>
    </li>
    <br>
    <li class="join-element padding-bottom-mini">
        <p class="join-label-box"><label class="join-label" for="input_email">이메일</label></P>
        <div class="join-input-box">
            <input class="join-input width-full" type="text" id="input_email" name="input_email">
            </select>
        </div>
        <p class="join-message"></p>
    </li>
    <li class="join-element padding-bottom-mini">
        <p class="join-label-box"><label class="join-label" for="input_nickname">닉네임</label></P>
        <div class="join-input-box">
            <input class="join-input width-full" maxlength="20" type="text" id="input_nickname" name="input_nickname">
        </div>
        <p class="join-message"></p>
    </li>

</ul>
<input type="button" class="long-button" id="btn_ok" value="가입하기">
</form>

<script>

    // 정규식 정의
    var check_id = /^[a-z0-9]{5,20}$/
    var check_pw = /^(?=.*[a-zA-Z])(?=.*[!@#$%^*+=-])(?=.*[0-9]).{8,16}$/;
    var check_email = /^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i;
    var check_nickname = /^[a-z0-9가-힣]{5,20}$/

    // id 유효성검사
    var tmp_id = $("#input_id")[0];
    tmp_id.addEventListener("blur",function(){
        console.log(tmp_id.value);
        if(!check_id.test(tmp_id.value)){
            set_msg(tmp_id, "5~20자의 영문 소문자, 숫자만 사용 가능합니다.");
        }

        else if(false){
            set_msg(tmp_id, "이미 가입되었거나 탈퇴한 아이디입니다.");
        }

        else{
            set_msg(tmp_id, "사용 가능한 아이디입니다.", "green")
        }
    });

    // 비밀번호 유효성검사
    tmp_pw = $("#input_pw")[0];
    tmp_pw.addEventListener("blur",function(){
        if(!check_pw.test(tmp_pw.value)){
            set_msg(tmp_pw, "8~16자의 영문 대소문자, 숫자 및 특수문자를 포함해야 합니다.");
        }else{
            set_msg(tmp_pw, "사용 가능한 비밀번호입니다.","green");
        }

    });

    // 비밀번호확인 유효성검사
    tmp_pwc = $("#input_pwc")[0];
    tmp_pwc.addEventListener("blur",function(){
        set_msg(tmp_pwc, tmp_pwc.value);
    });

    // 이메일 유효성검사
    tmp_em = $("#input_email")[0];
    tmp_em.addEventListener("blur",function(){
        set_msg(tmp_em, tmp_em.value);
    });

    // 닉네임 유효성검사
    tmp_nn = $("#input_nickname")[0];
    tmp_nn.addEventListener("blur",function(){
        set_msg(tmp_nn, tmp_nn.value);
    });

    function set_msg(input_element,msg, color="red"){
        var msg_element = input_element.closest("li").getElementsByClassName("join-message")[0];
        msg_element.innerText = msg;
        msg_element.style.color = color;
    }
</script>
