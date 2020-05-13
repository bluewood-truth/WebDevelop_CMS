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

    $board_group = sql_query("SELECT * FROM CMS_board_group ORDER BY order_nav");
 ?>
<script src="/ex_cms/common/common.js"></script>
<header id="header">
    <div class="screen-width">
        <span id="logo-mini"><a href="http://uraman.m-hosting.kr/ex_cms/">CMS WEB</a></span>
        <ul class="nav">
        <?
            while($group_row = sql_get_row($board_group)){
                $board = sql_query("SELECT * FROM CMS_board WHERE 'group_id' = ".$group_row["id"]." ORDER BY order_sub");
                echo '
                <li>
                    <a href="#">'.$group_row["name_kor"].'</a>
                    <ul class="nav-sub">';
                    while($board_row = sql_get_row($board)){
                        if(is_null($board_row["order_sub"]))
                            continue;
                        echo '<li><a href="http://uraman.m-hosting.kr/ex_cms/board/?id='.$board_row['id'].'">'.$board_row["name_kor"].'</a></li>';
                    }
                echo '
                    </ul>
                </li>';
            }
        ?>
        </ul>
        <input type="button" class="nav-btn" style="display:<? display_style('none','block') ?>" value="로그인" onclick="location.href='http://uraman.m-hosting.kr/ex_cms/login'">
        <input type="button" class="nav-btn" style="display:<? display_style('block','none') ?>" value="로그아웃" onclick="logout();">
        <span class="nav-msg" style="display:<? display_style('block','none') ?>"><b><? echo get_login_nickname() ?></b>님, 환영합니다.</span>
    </div>
</header>
