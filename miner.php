<?php
    require_once 'db.php';
    #'{"":""}';
    function check_user($nickname){
        $login_status = '';
        $sql = 'SELECT nickname from miner'; #DODAC TU id_key do searchu
        $query = mysqli_query($GLOBALS['conn'], $sql);
        while($row = mysqli_fetch_assoc($query)){
            if($nickname == $row['nickname']){
                $login_status = 'user exist';
                break;
            }
            else{
                $login_status = 'user doesnt exist';
            }
        }
        return $login_status;
    }

    function shutdow_ping($nickname){
        $delete = "DELETE FROM `miner` WHERE nickname='".$nickname."'";
        $query = mysqli_query($GLOBALS['conn'], $delete);
        if ($query) {
            mysqli_close($GLOBALS['conn']);
            return '{"status":"success"}';
        }
        else{
            mysqli_close($GLOBALS['conn']);
            return '{"status":"error"}';
        }
    }

    function update_status($status, $nickname){
        if($status == 'start' || $status == 'stop' || $status == 'exit'){
            $delete = "UPDATE `miner` SET `queue`='".$status."' WHERE `nickname`='".$nickname."'";
            $query = mysqli_query($GLOBALS['conn'], $delete);
            if ($query) {
                return "success";
            }
            else{
                return "error";
            }
        }
    }

    #function get_current_time(){
    #    $today = getdate();
    #    $date = date('Y-m-d h:i');#$today['year'].'-'.$today['mon'].'-'.$today['mday'];
        #echo date('Y-m-d', strtotime($date. ' + 1 days'));
        #$insert = "INSERT INTO `miner`(`status`, `queue`, `nickname`, `last_ping`) VALUES ('waiting','none','".$username."','')";
    #    echo $date;
    #}

    function get_activities($nickname, $key){
        $sql = "SELECT * FROM `miner` WHERE `nickname`='".$nickname."'";
        $query = mysqli_query($GLOBALS['conn'], $sql);
        while($row = mysqli_fetch_assoc($query)){
            return '{"status":"success","queue":"'.$row['queue'].'","key":"'.$key.'"}';
        }
        return '{"status":"error","key":"'.$key.'"}';
    }

    function update_activity($queue, $nickname, $key){
        $sql = "SELECT * from miner";
        $query = mysqli_query($GLOBALS['conn'], $sql);
        while($row = mysqli_fetch_assoc($query)){
            if($nickname == $row['nickname']){
                $update = update_status('stop', $nickname);
                $login_status = 'user exist';
                $delete = "UPDATE `miner` SET `queue`='". $queue ."',status='success' WHERE nickname='".$nickname."'";
                $query = mysqli_query($GLOBALS['conn'], $delete);
                if ($query) {
                    return '{"status":"success","queue":"'.$row['queue'].'","key":"'.$key.'"}';
                    #return "started ".$nickname."'s queue: ".$queue;
                }
                else{
                    return '{"status":"error","key":"'.$key.'"}';
                }
            }   
        }
    }

    function send_activities($mode, $nickname, $key){
        $user = check_user($nickname);
        if($user == 'user exist'){
            if($mode == 'start'){#$update == 'success'){
                $update = update_status('start', $nickname);
                if($update == 'success'){
                    return '{"status":"success","key":"'.$key.'"}';
                }
            }
            elseif($mode == 'stop'){
                $update = update_status('stop', $nickname);
                if($update == 'success'){
                    return '{"status":"success","key":"'.$key.'"}';
                }
            }
            elseif($mode == 'delete'){
                $sql = "DELETE FROM `miner` WHERE `nickname`='".$nickname."'";
                $query = mysqli_query($GLOBALS['conn'], $sql);
                if($query){
                    return '{"status":"success","key":"'.$key.'"}';
                }
            }
            else{
                return '{"status":"error","key":"'.$key.'"}';
            }
        }
        else{
            if($mode == 'insert'){
                $date = date('Y-m-d H:i:s');
                $sql = "INSERT INTO `miner`(`status`, `queue`, `nickname`, `last_ping`) VALUES ('running','start','".$nickname."','".$date."')";
                $query = mysqli_query($GLOBALS['conn'], $sql);
                if($query){
                    return '{"status":"success","key":"'.$key.'"}';
                }
                else{
                    return '{"status":"error","key":"'.$key.'"}';
                }
            }
        }
        return '{"status":"error","key":"'.$key.'"}';
    }
    
    #get_current_time();

    if($_POST['mode'] == 'start'){
        if(strlen($_POST['key']) == 12){
            $data = '{"status":"'.update_status($_POST['mode'], $_POST['nickname']).'","key":"'.$_POST['key'].'"}';
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
    elseif($_POST['mode'] == 'stop'){
        if(strlen($_POST['key']) == 12){
            $data = '{"status":"'.update_status($_POST['mode'], $_POST['nickname']).'","key":"'.$_POST['key'].'"}';
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
	elseif($_POST['mode'] == 'exit'){
        if(strlen($_POST['key']) == 12){
            $data = '{"status":"'.update_status($_POST['mode'], $_POST['nickname']).'","key":"'.$_POST['key'].'"}';
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
    elseif($_POST['mode'] == 'update_activities'){
        if(strlen($_POST['key']) == 12){
            $data = update_activity($_POST['queue'], $_POST['nickname'], $_POST['key']);
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
    elseif($_POST['mode'] == 'send_activities'){
        if(strlen($_POST['key']) == 12){
            $data = send_activities($_POST['activity'], $_POST['nickname'], $_POST['key']);
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
    elseif($_POST['mode'] == 'get_activities'){
        if(strlen($_POST['key']) == 12){
            $data = get_activities($_POST['nickname'], $_POST['key']);
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
    elseif($_POST['mode'] == 'shutdown'){
        if(strlen($_POST['key']) == 12){
            $data = shutdow_ping($_POST['nickname'], $_POST['key']);
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
?>