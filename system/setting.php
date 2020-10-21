<?php

session_start();

$http = '//'.$_SERVER['HTTP_HOST'];
if(isset($_SESSION['token']) == false){
	header('Location:'.$http.'/logout.php');
    exit;
}

$token = $_SESSION['token'];
include_once("../api/check.php");
$result=check($token);
if($result != true){
    header('Location:'.$http.'/logout.php');
    exit;
}

$info = $_SESSION['info'];

?>
<!doctype html>
<html lang="zh-Hant">
    <head>
        <?php include_once('../setup/head.php'); ?>
    </head>
    <body>
        <?php include_once('../setup/navbar.php'); ?>
		<h1 class="head" style="line-height:15vh;">致勝俱樂部</h1>
        <main id="main">
            <div class="container-fulid" style="margin:2vw;">
                <div style="height:10vh;">
                   <img src="/assets/img/users.png">
                </div>
            </div>
            <hr style="border-top:2px solid #ffffff;margin:2vh -2vw;">
              
            <div class="user-info">
                <span>會員編號：<?php echo $info['uid']; ?></span><br>
                <span>會員姓名：<?php echo $info['fullname']; ?></span><br>
                <span>電子信箱：<?php echo $info['email']; ?></span><br>
            </div>
            <div class="row setting-menu text-center justify-content-center">
                <div class="col-5" onclick="location.href='setting_activity.php';"><span>活動專區</span></div>
                <div class="col-5" onclick="location.href='setting_info.php';"><span>成員基本資料</span></div>
                <div class="col-5" onclick="location.href='setting_proxy.php';"><span>邀請好友加入俱樂部</span></div>
                <div class="col-5" onclick="location.href='setting_friend.php';"><span>我的俱樂部好友</span></div>
                <div class="col-5" onclick="location.href='setting_service.php';"><span>俱樂部客服</span></div>
            </div>
        </main>
    </body>
</html>