<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    if(isset($_SESSION["login"])){
        header('Location:http://uraman.m-hosting.kr/ex_cms/');
    }
 ?>

<!DOCTYPE html>
<html>
<head>
 	<meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/ex_cms/common/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="/ex_cms/common/common.js"></script>
</head>
<body>
    <? insert_parts("big_logo_header.html"); ?>
    <container>
        <div id="login-box" class="white-shadow-box">
            <form id="login-form" method="POST" action="_login_process.php">
                <div>
                    <input placeholder="아이디" name="id" class="login-input" type="text" required="reqired"><br>
                    <input placeholder="비밀번호" name="password" class="login-input" type="password" required="reqired">
                </div><br>
                <input type="button" class="custom-button long bg-orange" id="login_btn" value="로그인">
                <input type="button" class="custom-button long bg-gray" onclick="location.href='http://uraman.m-hosting.kr/ex_cms/join/join.php?page=agreement'" value="회원가입">
            </form>
            <br>
        </div>
    </container>
</body>

<script>
    $("#login_btn")[0].addEventListener("click",login)

    $(".login-input")[0].addEventListener("keyup",function(){
        if(window.event.keyCode == 13){
            login();
        }
    });
    $(".login-input")[1].addEventListener("keyup",function(){
        if(window.event.keyCode == 13){
            login();
        }
    });


    function login(){
        var id = $("#login-form")[0].id.value;
        var pw = $("#login-form")[0].password.value;

        var check = login_check(id,pw);
        console.log(check);
        if(check == true){
            $("#login-form")[0].submit();
        }
        else{
            alert("잘못된 아이디 또는 비밀번호입니다.")
        }
    }
</script>
</html>
