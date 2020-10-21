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
                   <img src="/assets/img/setting-service.png">
                </div>
            </div>

            <div style="height:50vh;margin-top:5vh;" onclick="location.href='https://lin.ee/XNMGQ0Q'">
                <img src="/assets/img/line.png" style="max-width:100%;max-height:50vh;">
            </div>
        </main>
    </body>
</html>