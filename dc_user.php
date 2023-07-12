<?php  
    require_once 'db.php';
    #'{"":""}';
    function check_username($nickname){
        $login_status = '';
        $sql = 'SELECT username from pvpb_dc';
        $query = mysqli_query($GLOBALS['conn'], $sql);

        while($row = mysqli_fetch_assoc($query)){
            if($nickname == $row['username']){
                $login_status = 'user exist';
                break;
            }
            else{
                $login_status = 'user doesnt exist';
            }
        }
        return $login_status;
    }

    function get_id($username, $key){
        $user_existing = check_username($username);
        if($user_existing == 'user exist'){

            $sql = 'SELECT username,discord_id from pvpb_dc';
            $query = mysqli_query($GLOBALS['conn'], $sql);
            
            while($row = mysqli_fetch_assoc($query)){
                if($username == $row['username']){
                    return '{"status":"success","discord_id":"'.$row['discord_id'].'","key":".'$key'."}';
                    #return 'success '.$row['discord_id'];
                }
                else{
                    $login_status = 'error';
                }
            }
            return '{"status":"'.$login_status.'","key":".'$key'."}';
        }
        else{
            return '{"status":"user doesnt exist","key":".'$key'."}';
        }
    }

    function insert_user($username, $user_discord_id, $key){
        $user_existing = check_username($username);
        if($user_existing == 'user exist'){
            $sql = "UPDATE `pvpb_dc` SET `discord_id`='".$user_discord_id."' WHERE `username`='".$username."'";
            $query = mysqli_query($GLOBALS['conn'], $sql);
            
            if($query){
                return '{"status":"success","key":".'$key'."}';
            }
            else{
                return '{"status":"error","key":".'$key'."}';
            }
        }
        else{
            $sql = "INSERT INTO pvpb_dc (username, discord_id) VALUES ('".$username."','".$user_discord_id."')";
            $query = mysqli_query($GLOBALS['conn'], $sql);
            
            if($query){
                return '{"status":"success","key":".'$key'."}';
            }
            else{
                return '{"status":"error","key":".'$key'."}';
            }
        }
    }
        
    function delete_user($username, $key){
        $user_status = '';
        $login = check_username($username);
        if($login == 'user exist'){

            $sql = "DELETE FROM pvpb_dc WHERE username='".$username."'";
            $query = mysqli_query($GLOBALS['conn'], $sql);
            
            if($query){
                return '{"status":"success","key":".'$key'."}';
            }
            else{
                return '{"status":"error","key":".'$key'."}';
            }
        }
        else{
            return '{"status":"user doesnt exist","key":".'$key'."}';
        }
    }

    function check_user($username, $user_discord_id){
        $user_status = '';
        $sql = 'SELECT username, discord_id FROM pvpb_dc';
        $query = mysqli_query($GLOBALS['conn'], $sql);

        while($row = mysqli_fetch_assoc($query)){
            if($username == $row['username'] && $user_discord_id == $row['discord_id']){
                $user_status = 'success';
                break;
            }
            else{
                $user_status = 'error';
            }
        }
        #return '{"status":"'.$user_status.'","key":".'$key'."}';
        return $user_status;
    }
    
    function reset_hwid($nickname, $user_discord_id, $key){
        $check = check_user($nickname, $user_discord_id);
        if($check == 'success'){
            $sql = "SELECT `user_hwid`,`user_hwid_status` FROM `users` WHERE `user_nickname`='".$nickname."'";
            $query = mysqli_query($GLOBALS['conn'], $sql);
            $row = mysqli_fetch_assoc($query);
            if($query){
                if($row['user_hwid_status'] == 'filled'){
                    $sql = "UPDATE `users` SET `user_hwid`='',`user_hwid_status`='empty' WHERE user_nickname='".$nickname."'";
                    $query = mysqli_query($GLOBALS['conn'], $sql);
                    if($query){
                        return '{"status":"success","key":".'$key'."}';
                    }
                    else{
                        return '{"status":"error","key":".'$key'."}';
                    }
                }
                else{
                    return '{"status":"already_empty","key":".'$key'."}';
                }
            }
            else{
                return '{"status":"user doesnt exist","key":".'$key'."}';
            }
        }
        else{
            return '{"status":"incorrect_id","key":"'.$key.'"}'
        }
    }

    if($_POST['mode'] == 'check_user'){
        if(strlen($_POST['key']) == 12){
            $data = check_user($_POST['username'], $_POST['discord_id']);
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
    elseif($_POST['mode'] == 'get_id'){
        if(strlen($_POST['key']) == 12){
            $data = get_id($_POST['username'], $_POST['key']);
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
    elseif($_POST['mode'] == 'insert_user'){
        if(strlen($_POST['key']) == 12){
            $data = insert_user($_POST['username'], $_POST['discord_id'], $_POST['key']);
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
    elseif($_POST['mode'] == 'delete_user'){
        if(strlen($_POST['key']) == 12){
            $data = delete_user($_POST['username'], $_POST['key']);
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
    elseif($_POST['mode'] == 'reset_hwid'){
        if(strlen($_POST['key']) == 12){
            $data = reset_hwid($_POST['username'], $_POST['discord_id'], $_POST['key']);
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
?>