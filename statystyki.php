<?php
    require_once 'db.php';
    include('./security.php');

    function return_top_data(){
        $data = array();
        $index = 1;

        $insert = "SELECT * FROM `statystyki` ORDER BY `wykopany_kamien` DESC";
        $query = mysqli_query($GLOBALS['conn'], $insert);
        while($row = mysqli_fetch_assoc($query)){
            if($index <= 7){
                $temp_array = array();
                foreach($row as $key => $val){
                    array_push($temp_array, $val);
                }
                array_push($data, $temp_array);
                $temp_array = array();
                $index += 1;
            }
            else{
                break;
            }
        }
        return json_encode($data);
    }

    function delete_user($nickname, $key){
        $insert = "DELETE FROM `statystyki` WHERE `nickname`='".$nickname."'";
        $query = mysqli_query($GLOBALS['conn'], $insert);
        if($query){
            return '{"status":"success","key":"'.$key.'"}';
        }
        return '{"status":"error","key":"'.$key.'"}';
    }

    function reset_stats($key){
        $insert = "UPDATE `statystyki` SET `zdobyte_kille`=0,`wykopany_kamien`=0,`zarobione_pieniadze`=0,`przebyte_kratki`=0";
        $query = mysqli_query($GLOBALS['conn'], $insert);
        if($query){
            return '{"status":"success","key":"'.$key.'"}';
        }
        return '{"status":"error","key":"'.$key.'"}';
    }

    function create_stats($nickname, $key){
        $sql = "INSERT INTO `statystyki`(`nickname`, `zdobyte_kille`, `wykopany_kamien`, `zarobione_pieniadze`, `przebyte_kratki`) VALUES ('".$nickname."',0,0,0,0)";
        $query = mysqli_query($GLOBALS['conn'], $sql);
        if ($query) {
            mysqli_close($GLOBALS['conn']);
            return '{"status":"success","key":"'.$key.'"}';
        }
        else{
            mysqli_close($GLOBALS['conn']);
            return '{"status":"error","key":"'.$key.'"}';
        }
    }

    function update_stats($stats, $value, $nickname, $key){
        if($stats == 'zdobyte_kille' || $stats == 'wykopany_kamien' || $stats == 'zarobione_pieniadze' || $stats == 'przebyte_kratki'){
            $delete = "UPDATE `statystyki` SET `".$stats."`=".$stats." + ".$value." WHERE nickname='".$nickname."'";
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
    }

    #FUNCTION RESET STATS

    if($_POST['mode'] == 'update_stats'){
        if(strlen($_POST['key']) == 12){
            $data = update_stats($_POST['stats'], $_POST['value'], $_POST['nickname'], $_POST['key']);
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
    elseif($_POST['mode'] == 'create_stats'){
        if(strlen($_POST['key']) == 12){
            $data = create_stats($_POST['nickname'], $_POST['key']);
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
    elseif($_POST['mode'] == 'reset_stats6345443543t3543'){
        if(strlen($_POST['key']) == 12){
            $data = reset_stats($_POST['key']);
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
    elseif($_POST['mode'] == 'delete_user'){
        if(strlen($_POST['key']) == 12){
            $data = delete_user($_POST['nickname'], $_POST['key']);
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
    elseif($_POST['mode'] == 'return_top_data'){
        if(strlen($_POST['key']) == 12){
            $data = return_top_data();
            $encrypted_data = encrypt($data);
            echo $encrypted_data;
        }
    }
?>