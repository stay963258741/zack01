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

$dashboard=getdashboard('home-gambling');

?>
<!doctype html>
<html lang="zh-Hant">
    <head>
        <?php include_once('../setup/head.php'); ?>
    </head>
    <body style = "margin:0px;">
        <?php include_once('../setup/navbar.php'); ?>
		<h1 class="head" style="line-height:15vh;">致勝俱樂部</h1>
        <main id="main">
            <!-- 30vh -->
            <div class="container-fulid" style="height:10vh;margin:0;">
                <div>
                   <img src="/assets/img/home-gambling.png" style="height:8vh;">
                </div>
            </div>

            <hr style="border-top:3px solid #ffffff;margin:2vh -3vw;">

            <div style="height:65vh;">
                <span style="line-height:5vh;font-size:3.5vh;color:#ffffff;">
                    <?php echo $dashboard['data_name'] ?>
                </span>

                <div class="home-list" style="word-wrap:break-word;" >
                    <?php echo $dashboard['data'] ?>
                    <img src="<?php echo $dashboard['data_pic'] ?>" style="height:auto;width:100%;"/>
                </div>
            </div>
        </main>
    </body>
</html>