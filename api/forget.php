<?php

$postData = file_get_contents('php://input');
$requests = !empty($postData) ? json_decode($postData, true) : array();

$switch=$requests['switch'];
$data=$requests['data'];


switch ($switch) {
    case "forget":
        $res = func_forget($data);
        echo json_encode($res);
        break;
    case "verify":
        $res = func_verify($data);
        echo json_encode($res);
        break;
    default:
        $output[0]=false;
        $output[1]='系統錯誤!';
        echo json_encode($output);
        break;
}

function func_forget($data){
    $output[0]=false;
    $output[1]='系統錯誤!';
    include_once("sql.php");
    $conn = new PDO("mysql:host=".constant("_server").";dbname=".constant("_db"), constant("_acc"), constant("_pwd"));
    $sth = $conn->prepare("SELECT * FROM `user_info` WHERE email=?");
    $sth1 = $conn->prepare("SELECT * FROM `verify_log` WHERE email=? ORDER BY DT DESC LIMIT 1");
 
    $sth_check = $conn->prepare("SELECT * FROM `user_info` WHERE `uid`=? AND `auth`=5");
    $sth2 = $conn->prepare("UPDATE `user_info` SET `pwd`=? WHERE `email`=? ");



    $sth->execute(array($data['email']));
    $count = $sth->rowCount();
    if($count != 1){
        $output[0]=false;
        $output[1]='E-mail未被註冊!';
    }else{
        $sth1->execute(array($data['email']));
        $count1 = $sth1->rowCount();
        $count_check = $sth_check->rowCount();
        if($count1 != 1){
            $output[0]=false;
            $output[1]='未申請驗證碼!';
        }else{
            $rows = $sth1->fetchAll(PDO::FETCH_ASSOC);
            if(strlen($rows[0]['verifycode']) != 6){
                $output[0]=false;
                $output[1]='未申請驗證碼!';
            }else if(strlen($data['verifycode']) != 6){
                $output[0]=false;
                $output[1]='驗證碼長度錯誤!';
            }else if($rows[0]['verifycode'] != $data['verifycode']){
                $output[0]=false;
                $output[1]='驗證碼錯誤!';
            }else{
                
                $sth2->execute(array(md5($data['pwd']),$data['email']));
                $count2 = $sth2->rowCount();
                if($count2 == 1){
                    $output[0]=true;
                    $output[1]='修改成功!';
                }else{
                    $output[0]=false;
                    $output[1]='修改錯誤!';
                }
            }
        }
    }
    return $output;
}

function func_verify($data){
    $output[0]=false;
    $output[1]='系統錯誤!';
    include_once("sql.php");
    
    $conn = new PDO("mysql:host=".constant("_server").";dbname=".constant("_db"), constant("_acc"), constant("_pwd"));
    $sth = $conn->prepare("SELECT * FROM `user_info` WHERE email=?");
    $sth2 = $conn->prepare("INSERT INTO `verify_log` (`email`, `verifycode`) VALUES (?,?)");
    $sth->execute(array($data['email']));
    $count = $sth->rowCount();
    if($count != 1){
        $output[0]=false;
        $output[1]='E-mail未被註冊!';
    }else{
        $verifycode = getrand_id();
        $sth2->execute(array($data['email'],$verifycode));
        $count2 = $sth2->rowCount();
        if($count2 == 1){
            $data['verifycode'] = $verifycode;
            if(func_sendverifymail($data)){
                $output[0]=true;
                $output[1]='請到信箱收取驗證信!';
            }else{
                $output[0]=false;
                $output[1]='驗證信發送失敗!';
            }
        }else{
            $output[0]=false;
            $output[1]='發生錯誤!';
        }
    }

    return $output;
}

function func_sendverifymail($data){
    include_once('mail.php');
    $send_data['title']='致勝俱樂部 驗證信';
    $send_data['body']='您的驗證碼為：'.$data['verifycode'];
    $send_data['email']=$data['email'];
    return send_mail($send_data);
}

function getrand_id(){
    $id_len = 6;//字串長度
    $id = '';
    $word = 'abcdefghijkmnpqrstuvwxyz23456789';//字典檔 你可以將 數字 0 1 及字母 O L 排除
    $len = strlen($word);//取得字典檔長度
 
    for($i = 0; $i < $id_len; $i++){ //總共取 幾次
        $id .= $word[rand() % $len];//隨機取得一個字元
    }
    return $id;//回傳亂數
}
function getnextuid(){
    $output='34657382';
    include_once("sql.php");
    $conn = new PDO("mysql:host=".constant("_server").";dbname=".constant("_db"), constant("_acc"), constant("_pwd"));
    $sth = $conn->prepare("SELECT uid FROM `user_info` WHERE `auth`=0 ORDER BY uid DESC LIMIT 1");
    $sth->execute();
    $count = $sth->rowCount();
    if($count == 0){
        $output='24657382';
    }else{
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        $output = $rows[0]['uid'] + 1;
    }
    return $output;
}
?>