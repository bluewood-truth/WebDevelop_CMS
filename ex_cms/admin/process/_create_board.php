<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/ex_cms/common/common.php";
    sql_connect();

    $bid = $_POST["bid"];
    $name = filter($_POST["name_kor"]);
    $on_menu = $_POST["display_on_menu"]=="true"?true:false;
    $cat_list = isset($_POST["cat"])?$_POST["cat"]:null;
    $access = $_POST["access"];
    $group = $_POST["group"];

    if(!strlen_check($name,1,7)){
        kick(1);
    }

    $new_list = "NULL";
    if(!is_null($cat_list)){
        $new_list = "";
        for($i=0; $i < count($cat_list); $i++){
            if(!strlen_check($cat_list[$i],1,7)){
                kick("2".$i.":".$cat_list[$i]);
            }
            $new_list = $new_list.$cat_list[$i]."|";
        }
        $new_list = "'".mb_substr($new_list, 0, -1)."'";
    }

    $order_sub = "NULL";
    if($on_menu){
        $result = sql_query("SELECT order_sub FROM CMS_board WHERE group_id='".$group."' ORDER BY order_sub DESC");
        $max_num = sql_get_row($result)["order_sub"];
        $order_sub = $max_num + 1;
    }


    $sql = "INSERT INTO CMS_board
        (id,name_kor,group_id,order_sub,access,category_list)
        VALUES ('".$bid."', '".$name."', '".$group."', ".$order_sub.", '".$access."', ".$new_list.")";
    echo $sql;
    exit;

    sql_query($sql);

    $sql = "CREATE TABLE CMS_post_".$bid." (
        	`id` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(255) NOT NULL,
            `content` text NOT NULL,
            `author_id` int(11) DEFAULT NULL,
            `author_nickname` varchar(255) DEFAULT NULL,
            `guest_name` varchar(255) DEFAULT NULL,
            `guest_password` varchar(255) DEFAULT NULL,
            `recommends` int(11) NOT NULL DEFAULT 0,
        	`views` int(11) NOT NULL DEFAULT 0,
        	`category` varchar(255) DEFAULT NULL,
        	`write_date` datetime NOT NULL,
            `is_notice` enum('none','board','global') NOT NULL,
            `comment_allowed` tinyint(1) NOT NULL DEFAULT 1,
            PRIMARY KEY(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    sql_query($sql);

    $sql = "CREATE TABLE CMS_comment_".$bid." (
        	`id` int(11) NOT NULL AUTO_INCREMENT,
            `content` text NOT NULL,
            `author_id` int(11) DEFAULT NULL,
            `author_nickname` varchar(255) DEFAULT NULL,
            `guest_name` varchar(255) DEFAULT NULL,
            `guest_password` varchar(255) DEFAULT NULL,
        	`post_id` int(11) NOT NULL,
        	`write_date` datetime NOT NULL,
            `root_comment_id` int(11) DEFAULT NULL,
            PRIMARY KEY(id),
            FOREIGN KEY ( post_id ) REFERENCES CMS_post_".$bid." ( id ) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    sql_query($sql);

    header("location:/ex_cms/admin/?tab=boards")
?>
