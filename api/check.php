<?php

function check($token){
    $result=false;

    include_once("sql.php");
    $conn = new PDO("mysql:host=".constant("_server").";dbname=".constant("_db"), constant("_acc"), constant("_pwd"));
    $conmand = "SELECT * FROM user_info WHERE token=?";

    $sth = $conn->prepare($conmand);
    $sth->execute(array($token));
    $row = $sth->fetchAll(PDO::FETCH_ASSOC);
    $count=COUNT($row);
    if($count==1){
        $result=true;
        $_SESSION['info']=$row[0];
    }
    $conn = null;
    return $result;
}

function getconfig($groupname){
    $output=null;
    include_once("sql.php");
    $conn = new PDO("mysql:host=".constant("_server").";dbname=".constant("_db"), constant("_acc"), constant("_pwd"));
    $conmand = "SELECT * FROM `config` WHERE data_group=?";
    $sth = $conn->prepare($conmand);
    $sth->execute(array($groupname));
    $row = $sth->fetchAll(PDO::FETCH_ASSOC);
    foreach($row as $data){
        $a=$data['data_group'];
        $output[$a] = $data['data'];
    }
    return $output;
}

function getdashboard($groupname){
    $output=null;
    include_once("sql.php");
    $conn = new PDO("mysql:host=".constant("_server").";dbname=".constant("_db"), constant("_acc"), constant("_pwd"));
    $conmand = "SELECT * FROM `config` WHERE data_group=?";
    $sth = $conn->prepare($conmand);
    $sth->execute(array($groupname));
    $row = $sth->fetchAll(PDO::FETCH_ASSOC);
    $output=$row[0];
    return $output;
}

function getuserbank($uid){
    $output=false;
    include_once("sql.php");
    $conn = new PDO("mysql:host=".constant("_server").";dbname=".constant("_db"), constant("_acc"), constant("_pwd"));
    $sth = $conn->prepare("SELECT * FROM `user_bank` WHERE `uid`=?");
    $sth->execute(array($uid));
    $row = $sth->fetchAll(PDO::FETCH_ASSOC);
    $count=COUNT($row);
    if($count == 1){
        $output=$row[0];
    }
    return $output;
}

function getusdtrate(){
    include_once("update_usdt.php");
    $rate=get_now_usdt();
    return $rate;
}

?>