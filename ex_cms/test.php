<?php
$test = "/^.{2,8}$/";
$txt = "ㅁㄴㅇㄹ";
echo mb_strlen($txt);
echo $txt."<br>";
var_dump(preg_match($test,$txt));

 ?>
