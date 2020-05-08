<?php
    // 게시판 id와 코멘트 id가 없으면 kick
    if(!isset($_GET["id"]) || !isset($_GET["cid"]) )
        kick();

    // prev_page가 없으면 kick (패스워드 체크 페이지를 넘어욌으면 있어야 함)
    if(!isset($_SESSION["prev_page"]))
        kick();

    $id = $_GET['id'];
    $cid = $_GET['cid'];


 ?>
