<?php
    if(isset($_POST["data"]) == false){
        invalid_access();
    }
    $table = sql_query("SELECT user_id,nickname FROM `CMS_userinfo` WHERE id=".$_POST['data']);
    $row = sql_get_row($table);
 ?>

<h2>회원가입이 완료되었습니다!</h2>
<hr>
<br>
<span class="basic-text">
모든 회원가입 절차가 완료되었습니다.<br>
로그인 후 서비스를 이용하실 수 있습니다.<br><br>
<?echo $row["nickname"]?>님의 아이디는 <b><?echo $row["user_id"]?></b>입니다.
</span>

<br><br>

<hr>

<br><br><br>
<input type="button" class="custom-button short bg-orange"  onclick="location.href='http://uraman.m-hosting.kr/ex_cms/login'" value="로그인">
<input type="button" class="custom-button short bg-gray" onclick="location.href='http://uraman.m-hosting.kr/ex_cms/'" value="메인화면">
