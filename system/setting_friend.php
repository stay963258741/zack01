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
                   <img src="/assets/img/setting-friend.png">
                </div>
            </div>
            <hr style="border-top:2px solid #ffffff;margin:2vh -2vw;">
            <div class="row" style="height:10vh;margin:0;">
                <div class="col">
                    <img src="/assets/img/setting-friend-first.png" style="max-width:100%;max-height:10vh;">
                </div>
                <div class="col">
                    <img src="/assets/img/setting-friend-two.png" style="max-width:100%;max-height:10vh;">
                </div>
                <div class="col">
                    <img src="/assets/img/setting-friend-three.png" style="max-width:100%;max-height:10vh;">
                </div>
            </div>
            <div class="home-list" style="height:55vh;">
                <div v-for="item in list_show">       
                    <div style="background-color:#29141460;border:0;line-height:5vh;text-align:left;">
                        暱　　稱：{{ item.fullname }}
                    </div>
                    <div style="background-color:transparent;border:0;line-height:5vh;text-align:left;">
                        編　　號：{{ item.uid }}<br>
                        等　　級：{{ item.auth }}<br>
                        電　　話：{{ item.phone }}<br>
                        直推人數：{{ item.push || 0 }}
                    </div>
                </div>
            </div>
        </main>
        <script>
            $(document).ready(function() {
                main.get_one();
            });   

            var main = new Vue({
                el:"#main",
                data:{
                    list_show:[]
                },
                methods:{
                    get_one : function(){
                        axios.post('/api/setting_friend.php', {
                            switch: 'get_one',
                            data:''
                        })
                        .then(function (response) {
                            if(response.data[0] == true){
                                main.list_show = response.data[1];
                            }else if(response.data[0] == false){
                                main.list_show=[];
                            }
                        })
                        .catch(function (error) {
                            //location.reload();
                        });
                    }
                }
            });
        </script>
    </body>
</html>
