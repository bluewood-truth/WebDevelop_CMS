<?php
    if(isset($_POST["valid_join"]) == false){
        header('Location:http://uraman.m-hosting.kr/ex_cms/');
        exit;
    }

    include $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    $id = filter($_POST["input_id"]);
    $pw = $_POST["input_pw"];
    $email = filter($_POST["input_email"]);
    $nickname = filter($_POST["input_nickname"]);

    // 정규식 정의
    $check_id = "/^[a-z0-9]{5,20}$/";
    $check_pw = "/^(?=.*[a-zA-Z])(?=.*[!@#$%^*+=-])(?=.*[0-9]).{8,16}$/";
    $check_email = "/^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i";
    $check_nickname = "/^.{2,8}$/";

    // 유효성 검사
    if(preg_match($check_id,$id) == False || preg_match($check_pw,$pw) == False || preg_match($check_email,$email) == False || preg_match($check_nickname,$nickname) == False){
        header('Location:http://uraman.m-hosting.kr/ex_cms/');
        exit;
    }

    $pw = sha1($pw); // 유효성 검사 이후 암호화

    // 중복 검사
    if(sql_duplicate_check($id,"CMS_userinfo","user_id") || sql_duplicate_check($nickname,"CMS_userinfo","nickname",True) || sql_duplicate_check($email,"CMS_userinfo","email",True)){
        header('Location:http://uraman.m-hosting.kr/ex_cms/');
        exit;
    }

    // db에 등록
    $sql = "INSERT INTO `CMS_userinfo` (user_id, password, email, nickname, join_date)
            VALUES('".$id."','".$pw."','".$email."','".$nickname."',now())";

    sql_query($sql);

    $id_array = mysqli_query($conn,"SELECT id FROM `CMS_userinfo` ORDER BY id DESC");
    $joined_id = mysqli_fetch_assoc($id_array)['id'];

    echo '<form action="http://uraman.m-hosting.kr/ex_cms/join/join.php?page=joined" method="POST" style="display:none">';
    echo '<input name="data" value="'.$joined_id.'">';
    echo '</form>';
 ?>

 <script>
     document.getElementsByTagName('form')[0].submit();
 </script>
