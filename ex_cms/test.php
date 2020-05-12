<?php
header('Content-Type: text/html; charset=utf-8');
$test = "/^.{2,8}$/";
$txt = "ㅁㄴㄹㄴ";
echo $txt;
var_dump(preg_match($test,$txt));

 ?>
