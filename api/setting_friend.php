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
    case "get_one":
        $res = func_get_one($data);
        echo json_encode($res);
        break;
    default:
        $output[0]=false;
        $output[1]='系統錯誤!';
        echo json_encode($output);
        break;
}

function func_get_one($data){
    $output[0]=false;
    $output[1]='系統錯誤!';

    $user_info = $_SESSION['info'];

    include_once("sql.php");
    $conn = new PDO("mysql:host=".constant("_server").";dbname=".constant("_db"), constant("_acc"), constant("_pwd"));
    $sth = $conn->prepare("SELECT * FROM `user_info` WHERE `proxy`=?");
    $sth->execute(array($user_info['uid']));
    $count = $sth->rowCount();
    if($count == 0){
        $output[0]=false;
        $output[1]='錯誤!';
    }else{
        $output[0]=true;
        $output[1]=$sth->fetchAll(PDO::FETCH_ASSOC);;
    }
    return $output;
}

?>