<?php 
    require_once('./db.php');
    include('./security.php');

    function get_online($key){
        $count = "SELECT COUNT(*) FROM `online`";
        $query = mysqli_query($GLOBALS['conn'], $count);
        while($row = mysqli_fetch_assoc($query)){
            return '{"status":"success","count":"'.$row['COUNT(*)'].'","key":"'.$key.'"}';
        }
        return '{"status":"error","key":"'.$key.'"}';
    }

    function set_online($nickname, $key){
        $delete = "INSERT INTO `online`(`nickname`) VALUES ('".$nickname."')";
        $query = mysqli_query($GLOBALS['conn'], $delete);
        if ($query) {
            mysqli_close($GLOBALS['conn']);
            return '{"status":"success","key":"'.$key.'"}';
        }
        else{
            mysqli_close($GLOBALS['conn']);
            return '{"status":"error","key":"'.$key.'"}';
        }
    }

    function delete_online($nickname, $key){
        $delete = "DELETE FROM `online` WHERE `nickname`='".$nickname."'";
        $query = mysqli_query($GLOBALS['conn'], $delete);
        if ($query) {
            mysqli_close($GLOBALS['conn']);
            return '{"status":"success","key":"'.$key.'"}';
        }
        else{
            mysqli_close($GLOBALS['conn']);
            return '{"status":"error","key":"'.$key.'"}';
        }
    }

    $mode = $_POST['mode'];
    if($_POST['mode'] == 'add'){
        if(strlen($_POST['key']) == 12){
            $data = set_online($nick_POST['nickname']ame, $_POST['key']);
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
    elseif($_POST['mode'] == 'delete'){
        if(strlen($_POST['key']) == 12){
            $data = delete_online($_POST['nickname'], $_POST['key']);
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
    elseif($_POST['mode'] == 'get'){
        if(strlen($_POST['key']) == 12){
            $data = get_online($_POST['key']);
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
?> 