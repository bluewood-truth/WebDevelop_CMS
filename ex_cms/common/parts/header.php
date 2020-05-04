<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    function display_style($login, $logout){
        if(isset($_SESSION['login'])){
            echo $login;
        }
        else{
            echo $logout;
        }
    }

    function nickname(){
        if(isset($_SESSION['login'])){
            $id = $_SESSION['login'];
            $sql = "SELECT nickname FROM CMS_userinfo WHERE user_id='".$id."'";
            $result = sql_query($sql);
            $result = sql_get_row($result);
            echo $result['nickname'];
        }
    }
 ?>
<script src="/ex_cms/common/common.js"></script>
<header class="header">
    <div class="screen-width">
        <span id="logo-mini"><a href="http://uraman.m-hosting.kr/ex_cms/">CMS WEB</a></span>

        <ul class="nav">
            <li>
                <a href="#">그룹1</a>
                <ul class="nav-sub">
                    <li><a href="#">서브1</a></li>
                    <li><a href="#">서브2</a></li>
                </ul>
            </li>
            <li>
                <a href="#">그룹2</a>
                <ul class="nav-sub">
                    <li><a href="#">서브1</a></li>
                    <li><a href="#">서브2</a></li>
                </ul>
            </li>
            <li>
                <a href="#">그룹3</a>
                <ul class="nav-sub">
                    <li><a href="#">서브1</a></li>
                    <li><a href="#">서브2</a></li>
                </ul>
            </li>
        </ul>
        <input type="button" class="nav-btn" style="display:<? display_style('none','block') ?>" value="로그인" onclick="location.href='http://uraman.m-hosting.kr/ex_cms/login'">
        <input type="button" class="nav-btn" style="display:<? display_style('block','none') ?>" value="로그아웃" onclick="logout();">
        <span class="nav-msg" style="display:<? display_style('block','none') ?>"><b><? nickname() ?></b>님, 환영합니다.</span>
    </div>
</header>
