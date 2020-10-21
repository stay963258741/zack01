<?php

$postData = file_get_contents('php://input');
$requests = !empty($postData) ? json_decode($postData, true) : array();

//$switch=$requests['switch'];
$data=$requests['data'];

/*
switch ($switch) {
    case "getinfo":
        func_getinfo($data);
        break;
    default:
        echo "Switch Error!";
        break;
}
*/

$res = func_login($data);
echo json_encode($res);

function func_login($data){
    $output[0]=false;
    $output[1]='系統錯誤!';
    include_once("sql.php");
    $conn = new PDO("mysql:host=".constant("_server").";dbname=".constant("_db"), constant("_acc"), constant("_pwd"));
    $sth = $conn->prepare("SELECT * FROM `user_info` WHERE email=?");
    $sth->execute(array($data['email']));
    $count = $sth->rowCount();
    if($count == 0){
        $output[0]=false;
        $output[1]='E-mail未註冊!';
    }else{
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        if(md5($data['pwd']) == $rows[0]['pwd']){
            func_getTOKEN($rows[0]['uid']);
            $output[0]=true;
            $output[1]='登入成功!';
        }else{
            $output[0]=false;
            $output[1]='密碼錯誤!';
        }
    }
    return $output;
}

function func_getTOKEN($data){
    session_start();
    $token=md5($data.time());
    include_once("sql.php");
    $conn = new PDO("mysql:host=".constant("_server").";dbname=".constant("_db"), constant("_acc"), constant("_pwd"));
    $sth = $conn->prepare("UPDATE `user_info` SET `token`=? WHERE `uid`=?");
    $sth->execute(array($token,$data));
    $count = $sth->rowCount();
    if($count == 1){
        $_SESSION['token'] = $token;
    }else{
        unset($_SESSION['token']);
    }
}

?>