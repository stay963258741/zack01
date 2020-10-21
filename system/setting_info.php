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
                   <img src="/assets/img/setting-info.png">
                </div>
            </div>
            <hr style="border-top:3px solid #ffffff;margin:2vh -3vw;">
            <div class="container-fulid" style="margin:2vw;">
                <div class="row">
                    <div class="col-12" style="margin:2vh 0;">
                        <input type="text" v-model="user_data.fullname" class="form-control blackinput" placeholder="輸入姓名" style="background-color:#70707099;color:#ffffff;border:0;">
                    </div>
                
                    <div class="col-12" style="margin:2vh 0;">
                        <input type="text" v-model="user_data.nickname" class="form-control blackinput" placeholder="暱稱" style="background-color:#70707099;color:#ffffff;border:0;">
                    </div>
                
                    <div class="col-12" style="margin:2vh 0;">
                        <input type="text" v-model="user_data.birth" class="form-control blackinput" placeholder="生日 EX:99/09/09" style="background-color:#70707099;color:#ffffff;border:0;">
                    </div>
                
                    <div class="col-12">
                        <button class="btn btn-lg btn-block picbtn" @click="save1" style="background-image:url('/assets/img/go.png');"></button>
                    </div>
                </div> 
            </div>
        </main>
        <script>
            var main = new Vue({
                el: '#main',
                data: {
                    user_data:{
                        'fullname':'<?php echo $info['fullname']; ?>',
                        'nickname':'<?php echo $info['nickname']; ?>',
                        'birth':'<?php echo $info['birth']; ?>',
                    }
                },
                methods: {
                    save1: function () {
                        var fullname = this.user_data.fullname;
                        var nickname = this.user_data.nickname;
                        
                        if(fullname.lenght < 2){
                            Swal.fire({ icon: 'error', title: '名字過短!',timer:1500 });
                        }else if(nickname.lenght > 5){
                            Swal.fire({ icon: 'error', title: '暱稱過長!',timer:1500 });
                        }else{
                            console.log(this.user_data);
                            axios.post('/api/user_setting.php', {
                                    switch: 'update_info',
                                    data: this.user_data
                                })
                                .then(function (response) {
                                    if(response.data[0] == true){
                                        Swal.fire({ icon: 'success', title: response.data[1],timer:1500 });
                                    }else{
                                        Swal.fire({ icon: 'error', title: response.data[1],timer:1500 });
                                    }
                                })
                                .catch(function (error) {
                                    //location.reload();
                            });
                        }
                    }
                }
            });
        </script>
    </body>
</html>