<?php

    require_once('./db.php');
    #'{"":""}';
    function status($key){
        return '{"status":"on going","version":"1.0","key":"'.$key.'"}';
    }
    
    $mode = $_POST['mode'];
    if($mode == 'status'){
        if(strlen($_POST['key']) == 12){
            $data = status($_POST['key']);
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
?>