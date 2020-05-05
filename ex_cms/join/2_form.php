<?php
    if(isset($_POST["aggrement"]) == false){
        invalid_access();
    }
 ?>
<form action="_join_process.php" method="post" id="form">
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

<br>

<input type="button" class="custom-button short bg-gray" onclick="location.href='http://uraman.m-hosting.kr/ex_cms/login'" value="취소">
<input type="button" class="custom-button short bg-orange" id="btn_ok" value="가입하기">
</form>

<script>
    // 정규식 정의
    var check_id = /^[a-z0-9]{5,20}$/;
    var check_pw = /^(?=.*[a-zA-Z])(?=.*[!@#$%^*+=-])(?=.*[0-9]).{8,16}$/;
    var check_email = /^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i;
    var check_nickname = /^.{2,8}$/;

    // 유효성 체크용 변수
    var checked = [];
    checked["id"] = false;
    checked["pw"] = false;
    checked["pwc"] = false;
    checked["email"] = false;
    checked["nickname"] = false;

    // id 유효성검사
    var tmp_id = $("#input_id")[0];
    tmp_id.addEventListener("blur",function(){
        // 유효성검사
        if(!check_id.test(tmp_id.value)){
            set_msg(tmp_id, "X 5~20자의 영문 소문자, 숫자만 사용 가능합니다.");
            checked["id"] = false;
        }
        // 중복검사
        else if(duplicate_check(tmp_id.value,"CMS_userinfo","user_id")){
            set_msg(tmp_id, "X 이미 가입되었거나 탈퇴한 아이디입니다.");
            checked["id"] = false;
        }
        // 가능
        else{
            set_msg(tmp_id, "O 사용 가능한 아이디입니다.", "green")
            checked["id"] = true;
        }
    });

    // 비밀번호 유효성검사
    var tmp_pw = $("#input_pw")[0];
    tmp_pw.addEventListener("blur",function(){
        // 유효성검사
        if(!check_pw.test(tmp_pw.value)){
            set_msg(tmp_pw, "X 8~16자의 영문 대소문자, 숫자 및 특수문자를 포함해야 합니다.");
            checked["pw"] = false;
        }
        // 가능
        else{
            set_msg(tmp_pw, "O 사용 가능한 비밀번호입니다.","green");
            checked["pw"] = true;
        }

        // 비밀번호확인과 비교
        if(tmp_pwc.value != tmp_pw.value){
            set_msg(tmp_pwc,"X 비밀번호가 일치하지 않습니다.");
            checked["pwc"] = false;
        }else{
            set_msg(tmp_pwc,"O 비밀번호가 일치합니다.", "green");
            checked["pwc"] = true;
        }
    });

    // 비밀번호확인 유효성검사
    var tmp_pwc = $("#input_pwc")[0];
    tmp_pwc.addEventListener("blur",function(){
        // 길이가 0이면 아무것도 표시 안함
        if(tmp_pwc.value.length == 0){
            set_msg(tmp_pwc,"");
            return;
        }

        // 비밀번호 일치 검사
        if (tmp_pw.value != tmp_pwc.value){
            set_msg(tmp_pwc,"X 비밀번호가 일치하지 않습니다.");
            checked["pwc"] = false;
        }
        // 가능
        else{
            set_msg(tmp_pwc,"O 비밀번호가 일치합니다.", "green");
            checked["pwc"] = true;
        }
    });

    // 이메일 유효성검사
    var tmp_em = $("#input_email")[0];
    tmp_em.addEventListener("blur",function(){
        // 이메일 유효성검사
        if(!check_email.test(tmp_em.value)){
            set_msg(tmp_em, "X 올바른 이메일을 입력해주세요.");
            checked["email"] = false;
        }
        // 중복검사
        else if(duplicate_check(tmp_em.value,"CMS_userinfo","email",true)){
            set_msg(tmp_em, "X 사용 중인 이메일입니다.");
            checked["email"] = false;
        }
        // 가능
        else{
            set_msg(tmp_em, "O 사용 가능한 이메일입니다.","green");
            checked["email"] = true;
        }
    });

    // 닉네임 유효성검사
    var tmp_nn = $("#input_nickname")[0];
    tmp_nn.addEventListener("blur",function(){
        // 유효성검사
        if(!check_nickname.test(tmp_nn.value)){
            set_msg(tmp_nn, "X 2~8자의 닉네임만 사용 가능합니다.");
            checked["nickname"] = false;
        }
        // 중복검사
        else if(duplicate_check(tmp_nn.value,"CMS_userinfo","nickname",true)){
            set_msg(tmp_nn, "X 사용 중인 닉네임입니다.");
            checked["nickname"] = false;
        }
        // 가능
        else{
            set_msg(tmp_nn, "O 사용 가능한 닉네임입니다.", "green")
            checked["nickname"] = true;
        }
    });

    // 확인버튼
    $("#btn_ok")[0].addEventListener("click",function(){
        for(key in checked){
            if(checked[key] == false){
                alert("모든 입력사항을 올바르게 작성해주세요.");
                return;
            }
        }

        set_post("form","valid_join","true");

        $("#form").submit();
    });


    function set_msg(input_element,msg, color="red"){
        var msg_element = input_element.closest("li").getElementsByClassName("join-message")[0];
        msg_element.innerText = msg;
        msg_element.style.color = color;
    }

</script>
