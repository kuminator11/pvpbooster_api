<?php
    require_once 'db.php';
    #'{"":""}';
    function keyExists($license_key){
        $key_status = 'not found';
        $check = 'SELECT license_key from keystatus';
        $query = mysqli_query($GLOBALS['conn'], $check);
        while($row = mysqli_fetch_assoc($query)){
            if($license_key == $row['license_key']){
                $key_status = 'found';
                break;
            }
            else{
                $key_status = 'not found';
            }
        }
        mysqli_free_result($query);
        return $key_status;
    }

    function deleteKey($license_key, $key){
        $key_duplicate = keyExists($license_key);
        if($key_duplicate[0] == 'found'){
            $delete = 'DELETE FROM keystatus WHERE id_key='.$key_duplicate[1];
            $query = mysqli_query($GLOBALS['conn'], $delete);
            if ($query) {
                return '{"status":"success","key":"'.$key.'"}';
            }
            else{
                return '{"status":"error","key":"'.$key.'"}';
            }
        }
        else{
            return '{"status":"key was not found","key":"'.$key.'"}';
        }
    }

    function assertKey($license_key, $key_type, $key_durability, $key){
        $key_duplicate = keyExists($license_key);
        if($key_duplicate == 'not found'){

            $insert = "INSERT INTO keystatus (license_type , license_key, license_expire_day) values 
                    ('".$key_type."', '".$license_key."', '".$key_durability."')";
            $sqlquery = mysqli_query($GLOBALS['conn'], $insert);
            if($sqlquery){
                return '{"status":"success","key":"'.$key.'"}';
            }
            else{
                return '{"status":"error","key":"'.$key.'"}';
            }
        }
        else{
            return '{"status":"key already exists","key":"'.$key.'"}';
        }
    }

    if($_POST['mode'] == 'key_exists'){
        if(strlen($_POST['key']) == 12){
            $data = keyExists($_POST['license_key'], $_POST['key']);
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
    elseif($_POST['mode'] == 'assert_key'){
        if(strlen($_POST['key']) == 12){
            $data = assertKey($_POST['license_key'], $_POST['key_type'], $_POST['key_durability'], $_POST['key']);
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
    elseif($_POST['mode'] == 'delete_key'){
        if(strlen($_POST['key']) == 12){
            $data = deleteKey($_POST['license_key'], $_POST['key']);
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
?>