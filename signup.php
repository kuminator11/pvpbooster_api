<?php  
    require_once 'db.php';
    include('./security.php');
    
    #'{"":""}';
    function check_hwid($hwid, $key){
        $insert = "SELECT * FROM `bans` WHERE 1";
        $query = mysqli_query($GLOBALS['conn'], $insert);
        while($row = mysqli_fetch_assoc($query)){
            if($row['hwid'] == $hwid){
                return '{"status":"banned","key":"'.$key.'"}';
            }
        }
        return '{"status":"success","key":"'.$key.'"}';
    }

    function delete_key($key){
        $insert = "DELETE FROM `keystatus` WHERE license_key='".$key."'";
        $query = mysqli_query($GLOBALS['conn'], $insert);
        if($query){
            return 'success';
        }
        return 'error';
    }
    function get_key_details($key){
        $key_status = 'not found';
        $insert = "SELECT * FROM `keystatus`";
        $query = mysqli_query($GLOBALS['conn'], $insert);
        while($row = mysqli_fetch_assoc($query)){
            if($key == $row['license_key']){
                $date = date('Y-m-d');
                $expire_date = date('Y-m-d', strtotime($date. ' + '.$row['license_expire_day'].' days'));
                $key_status = $row['license_type'].' '.$expire_date;
                break;
            }
        }
        return $key_status;
    }
    function check_username($nickname){
        $login_status = '';
        $user_data = '';
        $sql = 'SELECT user_nickname from users';
        $query = mysqli_query($GLOBALS['conn'], $sql);

        while($row = mysqli_fetch_assoc($query)){
            if($nickname == $row['user_nickname']){
                $login_status = 'user exist';
                $user_data = implode(' ', $row);
                break;
            }
            else{
                $login_status = 'user doesnt exist';
            }
        }
        return $login_status;
    }
    function sign_up($personal_id, $email, $nickname, $password, $hwid, $hwid_status, $license_key, $registration_date, $key){
        $taken_login = check_username($nickname);
        if($taken_login == 'user doesnt exist'){
            $key_status = get_key_details($license_key);
            $key_type = explode(' ', $key_status);
            if($key_status !== 'not found'){
                $insert = "INSERT INTO users (user_personal_id, user_email, user_nickname,
                user_password, user_hwid, user_hwid_status, user_license_type, user_license_key,
                user_license_expire_date, user_join_date) values ('".$personal_id."','".$email."',
                '".$nickname."','".$password."','".$hwid."','".$hwid_status."','".$key_type[0]."','".$license_key."',
                '".$key_type[1]."','".$registration_date."')";
                $result = mysqli_query($GLOBALS['conn'], $insert);
                if($result){
                    $delete_key = delete_key($license_key);
                    if($delete_key == 'success'){
                        mysqli_close($GLOBALS['conn']);
                        return '{"status":"success","key":"'.$key.'"}';
                    }
                    else{
                        mysqli_close($GLOBALS['conn']);
                        return '{"status":"error","key":"'.$key.'"}';
                    }
                }
                else{
                    mysqli_close($GLOBALS['conn']);
                    return '{"status":"error","key":"'.$key.'"}';
                }
            }
            else{
                mysqli_close($GLOBALS['conn']);
                return '{"status":"key was not found","key":"'.$key.'"}';
            }
        }
        else{
            mysqli_close($GLOBALS['conn']);
            return '{"status":"user exist","key":"'.$key.'"}';
        }
    }

    if($_POST['mode'] == 'add_user'){
        if(strlen($_POST['key']) == 12){
            $data = sign_up($_POST['personal_id'], $_POST['email'], $_POST['nickname'], $_POST['password'], $_POST['hwid'], $_POST['hwid_status'], $_POST['license_key'], $_POST['registration_date'], $_POST['key']);
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
    elseif($_POST['mode'] == 'check_hwid'){
        if(strlen($_POST['key']) == 12){
            $data = check_hwid($_POST['hwid'], $_POST['key']);
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
?>