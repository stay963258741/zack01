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
    
    <body style = "margin:0px;">
        <?php include_once('../setup/navbar.php'); ?>
		<h1 class="head" style="line-height:15vh;">致勝俱樂部</h1>
        <main id="main">
			<div class="container">
				<div style="height:35vh;background-image:url('/assets/img/setting-proxy-qrcode.png');background-size:auto 100%;background-position:center;background-repeat:no-repeat;">
					<div style="height:5vh;"></div>
					<img src="/assets/img/setting-proxy-myqr.png" style="height:30vh;max-width:100%;">
				</div>
			</div> 
			<div id="copyWeChat" style="margin-top:15vh;">
				<input type="text" readonly value="<?php echo 'https:'.$http.'/register.php?p='.$info['proxy_md5']; ?>" class="form-control blackinput" placeholder="我的邀請碼" style="background-color:transparent;color:#ffffff;border:5;">  
			</div>

			<div style="height:10vh;margin-top:3vh">
				<bottom class="btn btn-lg btn-block picbtn" onclick="copyFn('copyWeChat')" style="background-image:url('/assets/img/setting-proxy-copyaddress.png')"></bottom>
			</div>
        </main>
        <script>
            function copyFn(id){
                var val = document.getElementById(id);
                window.getSelection().selectAllChildren(val);
                document.execCommand ("Copy");
                alert("複製成功")
            }
        </script>
    </body>
</html>