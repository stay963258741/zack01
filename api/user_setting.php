<?php

$postData = file_get_contents('php://input');
$requests = !empty($postData) ? json_decode($postData, true) : array();

$switch=$requests['switch'];
$data=$requests['data'];

//先檢查會員token
session_start();
include_once("check.php");
if(isset($_SESSION['token'])){
    $token = $_SESSION['token'];
    $check_user=check($token);
    if($check_user == false){
        $output[0]=false;
        $output[1]='登入逾時，請重新登入!';
        echo json_encode($output);
        return false;
    }
}else{
    $output[0]=false;
    $output[1]='尚未登入，請重新登入!';
    echo json_encode($output);
    return false;
}

switch ($switch) {
    case "update_info":
        $res = func_update_info($data);
        echo json_encode($res);
        break;
    default:
        $output[0]=false;
        $output[1]='系統錯誤';
        echo json_encode($output);
        break;
}

function func_update_info($data){
    
    $output[0]=false;
    $output[1]='系統錯誤!';

    $user_info = $_SESSION['info'];

    include_once("sql.php");
    $conn = new PDO("mysql:host=".constant("_server").";dbname=".constant("_db"), constant("_acc"), constant("_pwd"));
    $sth_check = $conn->prepare("SELECT * FROM `user_info` WHERE `uid`=? ");
    $sth_update = $conn->prepare("UPDATE `user_info` SET `fullname`=?,`nickname`=?,`birth`=? WHERE `uid`=?");
    
    $sth_check->execute(array($user_info['uid']));
    $count_check = $sth_check->rowCount();
    if($count_check == 1){
        $sth_update->execute(array($data['fullname'],$data['nickname'],$data['birth'],$user_info['uid']));
        $count_update = $sth_update->rowCount();
        if($count_update ==1){
            $output[0]=true;
            $output[1]='更新成功!';
        }else{
            $output[0]=false;
            $output[1]='更新失敗!';
        }
    }else{
        if($count_check >= 2){
            $sth_delete->execute(array($user_info['uid']));
        }
        $sth_insert->execute(array($user_info['uid'],$data['bank_name'],$data['bank_code'],$data['bank_branch'],$data['bank_account'],$data['bank_user']));
        $count_insert = $sth_insert->rowCount();
        if($count_insert ==1){
            $output[0]=true;
            $output[1]='新增成功!';
        }
    }

    return $output;
}

?>