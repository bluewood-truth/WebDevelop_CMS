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

    $admin_logined = access_check("admin");
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
                    <a class="nav-menu" href="">'.$group_row["name_kor"].'</a>
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
        <div id="nav-userinfo">
            <?if($admin_logined) echo '<a href="/ex_cms/admin?tab=members"><img class="icon" src="/ex_cms/images/icon_admin_page.png" ></a>';?>
            <span class="nav-msg" style="display:<? display_style('block','none') ?>"><a style="font-weight:bold; color:#eee;" href="/ex_cms/mypage/?tab=info"><? echo get_login_nickname() ?></a>님, 환영합니다.</span>
            <input type="button" class="nav-btn" style="display:<? display_style('block','none') ?>" value="로그아웃" onclick="logout();">
            <input type="button" class="nav-btn" style="display:<? display_style('none','block') ?>" value="로그인" onclick="location.href='/ex_cms/login'">
        </div>
    </div>
    <script>
        for(var i = 0; i < $(".nav-menu").length; i++){
            $(".nav-menu")[i].href = $(".nav-menu")[i].closest("li").getElementsByTagName("li")[0].childNodes[0].href;
        }
    </script>
</header>
