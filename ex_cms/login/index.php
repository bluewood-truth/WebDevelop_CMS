<?php
    include $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
     ?>

<!DOCTYPE html>
<html>
<head>
 	<meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/ex_cms/common/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
    <? insert_parts("big_logo_header"); ?>
    <container>
        <div id="login-box">
            <form id="login-form" method="POST" action="login_process.php">
                <div>
                    <input placeholder="아이디" name="id" class="login-input" type="text" required="reqired"><br>
                    <input placeholder="비밀번호" name="password" class="login-input" type="password" required="reqired">
                </div>
                <input type="submit" class="custom-button long bg-orange" value="로그인">
            </form>
            <a id="join" href="http://uraman.m-hosting.kr/ex_cms/join/join.php?page=agreement">회원가입</a>
            <br>
        </div>
    </container>
</body>
</html>
