<?php
    $sql = "SELECT * FROM CMS_userinfo WHERE id=".$_SESSION["login"];
    $member = sql_get_row(sql_query($sql));
?>
<div id="mypage-info">
    <table>
        <tbody>
            <tr>
                <td class="label">아이디</td>
                <td class="value"><?echo $member["user_id"]?></td>
            </tr>
            <tr>
                <td class="label">닉네임</td>
                <td class="value">
                    <span id="nickname-now"><?echo $member["nickname"]?></span>
                    <form id="modify-nickname" class="invisible" action="process/_modify_nickname_process.php" method="post">
                        <input class="text" minlength="2" maxlength="8" required=required type="text" name="nickname" value="<?echo $member["nickname"]?>">
                        <input class="ok" type="submit" name="" value="확인">
                        <input class="cancel" type="button" onclick="modify_nickname(false)" name="" value="취소">
                    </form>
                </td>
            </tr>
            <tr>
                <td class="label">이메일</td>
                <td class="value"><?echo $member["email"]?></td>
            </tr>
            <tr>
                <td class="label">가입일</td>
                <td class="value"><?echo $member["join_date"]?></td>
            </tr>
        </tbody>
    </table>
    <div id="buttons">
        <button id="modify-nickname-button" type="button" onclick="modify_nickname(true)" class="btn-mini bg-orange">닉네임 변경</button>
        <button id="withdrawal-button" type="button" class="btn-mini bg-gray">회원탈퇴</button>
    </div>
</div>


<script>
    function modify_nickname(is_on){
        if(is_on){
            $("#nickname-now")[0].classList.add("invisible");
            $("#modify-nickname")[0].classList.remove("invisible");
        }
        else{
            $("#nickname-now")[0].classList.remove("invisible");
            $("#modify-nickname")[0].classList.add("invisible");
        }
    }

    // 닉네임 유효성검사
    var tmp_nn = $("#modify-nickname .text")[0];
    $("#modify-nickname")[0].addEventListener("submit",function(event){
        // 중복검사
        if(duplicate_check(tmp_nn.value,"CMS_userinfo","nickname",true)){
            alert("사용 중인 닉네임입니다.");
            event.preventDefault();
        }
    });
</script>
