<form action="process/_create_group.php" method="post" style="text-align:center">
    <div id="group-info">
        <table>
            <tr>
                <td class="label">게시판그룹명</td>
                <td id="name" class="value">
                    <span></span>
                    <div id="modify-groupname-box">
                        <input class="text" minlength="1" maxlength="7" type="text" name="group_name" value="">
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="buttons">
        <input style="font-size:13.333px" id="done" type="submit" value="확인" class="btn-mini bg-orange">
        <input style="font-size:13.333px" type="button" value="취소" class="btn-mini bg-gray" onclick="location.href='?tab=boards'">
    </div>
</form>
