<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    if(!isset($_SESSION["login"]))
        kick(0);
    $my_authority = sql_get_row(sql_query("SELECT is_admin FROM CMS_userinfo WHERE id=".$_SESSION["login"]))["is_admin"];
    if($my_authority != "admin" && $my_authority != "super_admin")
        kick(01);

    if(!isset($_POST["checked"]))
        kick(11);
    $checked = $_POST["checked"];

    $action;
    if(isset($_POST["authority_change"]))
        $action = "authority_change";
    else if(isset($_POST["ban"]))
        $action = "ban";
    else if(isset($_POST["delete"]))
        $action = "delete";
    else if(isset($_POST["release_ban"]))
        $action = "release_ban";
    else
        kick(12);

    for($i = 0; $i < count($checked); $i++){

        $id = $checked[$i];

        if($action=="release_ban"){
            $sql = "DELETE FROM CMS_user_banlist WHERE user_id=".$id;
            sql_query($sql);
            continue;
        }

        $result = sql_query("SELECT is_admin FROM CMS_userinfo WHERE id=".$checked[$i]);
        if(sql_get_num_rows($result) == 0)
            continue;
        $member_authority = sql_get_row($result)["is_admin"];
        if(($my_authority == "admin" && $member_authority == "admin" )|| $member_authority == "super_admin")
            continue;

        switch($action){
            case "authority_change":
                if(!isset($_POST["authority"]))
                    kick(21);
                $value;
                if($_POST["authority"] == "관리자")
                    $value = "admin";
                else if ($_POST["authority"] == "회원")
                    $value = "member";
                else
                    kick(22);

                $sql = "UPDATE CMS_userinfo SET is_admin='".$value."' WHERE id=".$id;
                sql_query($sql);
                break;
            case "ban":
                if(!isset($_POST["ban_period"]))
                    kick(31);
                $end_date = strtotime("+".$_POST["ban_period"]." days");
                $end_date = date("Y-m-d H:i:s", $end_date);
                $already_ban = sql_duplicate_check($id,"CMS_user_banlist","user_id");
                $sql;
                if($already_ban)
                    $sql = "UPDATE CMS_user_banlist SET end_date=".$end_date." WHERE id=".$id;
                else
                    $sql = "INSERT INTO CMS_user_banlist (user_id,end_date) VALUES('".$id."','".$end_date."')";
                sql_query($sql);
                break;
            case "delete":
                $sql = "UPDATE CMS_userinfo SET deleted=1 WHERE id=".$id;
                sql_query($sql);
                break;
        }
    }

    switch($action){
        case "authority_change":
            invalid_access("권한 변경이 완료되었습니다.");
            break;
        case "ban":
            invalid_access("정지가 완료되었습니다.");
            break;
        case "delete":
            invalid_access("탈퇴되었습니다.");
            break;
        case "release_ban":
            invalid_access("정지가 해제되었습니다.");
            break;
    }
?>
