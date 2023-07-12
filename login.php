<?php 
    require_once('./db.php');
    #error_reporting(0);
    #require __DIR__ . '/main.php';
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

    function update_hwid($nickname, $hwid, $key){
        #UPDATE `users` SET `user_hwid`='03C00218-044D-0544-0706-EB0700080009',`user_hwid_status`='set' WHERE `user_nickname`='gowno'
        $delete = "UPDATE `users` SET `user_hwid`='".$hwid."',`user_hwid_status`='filled' WHERE user_nickname='".$nickname."'";
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

    function check_data($nickname, $password, $hwid, $key){
        $login_status = '';
        $user_data = '';
        $hwid_status = '';
        $sql = 'SELECT user_personal_id, user_nickname, user_password, user_hwid, user_hwid_status, user_license_type, user_license_expire_date from users'; #DODAC TU id_key do searchu
        $query = mysqli_query($GLOBALS['conn'], $sql);
        while($row = mysqli_fetch_assoc($query)){
            if($nickname == $row['user_nickname']){
                $login_status = 'user exist';
                $user_data = implode(' ', $row);
                $hwid_status = $row['user_hwid_status'];
                break;
            }
            else{
                $login_status = 'user doesnt exist';
            }
        }
        if($login_status == 'user doesnt exist'){
            return '{"status":"user doesnt exist","key":"'.$key.'"}';
        }
        else{
            if($hwid_status == 'empty'){
                return '{"status":"empty hwid slot","key":"'.$key.'"}';
            }
            else{

                $array = explode(' ', $user_data);
                if($array[1] == $nickname && $array[2] == $password && $array[3] == $hwid){
                    $date = date('Y-m-d');
                    if ($array[6] > $date){
                        return '{"status":"success","key":"'.$key.'","license":"'.$array[5].'","license_expire_date":"'.$array[6].'","hwid_status":"'.$array[4].'","personal_id":"'.$array[0].'","nickname":"'.$array[1].'"}';
                        #return 'success '.$array[5].' '.$array[6].' '.$array[4].' '.$array[0].' '.$array[1];
                    }
                    return '{"status":"expired license","key":"'.$key.'"}';
                }
                else{
                    return '{"status":"incorrect data","key":"'.$key.'"}';
                }
            }
        }
    }

    $mode = $_POST['mode'];
    #if($_POST['mode'] == 'check_data' && empty($_POST['mode']) == FALSE) && empty($_POST['nickname']) == FALSE && empty($_POST['password']) == FALSE && empty($_POST['hwid']) == FALSE && empty($_POST['key']) == FALSE{
    if($_POST['mode'] == 'check_data'){
        if(strlen($_POST['key']) == 12){
            $data = check_data($_POST['nickname'], $_POST['password'], $_POST['hwid'], $_POST['key']);
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
    #elseif($_POST['mode'] == 'update_hwid' && empty($_POST['mode']) == FALSE && empty($_POST['nickname']) == FALSE && empty($_POST['hwid']) == FALSE){
    elseif($_POST['mode'] == 'update_hwid'){
        if(strlen($_POST['key']) == 12){
            $data = update_hwid($_POST['nickname'], $_POST['hwid'], $_POST['key']);
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

    //sprawdzic czy pole license_expire_date jest puste to wtedy nie ma licencji
?> 