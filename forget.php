<?php

session_start();

if(isset($_SESSION['token'])){
	$token = $_SESSION['token'];
	include_once("./api/check.php");
	$result=check($token);
	$http = '//'.$_SERVER['HTTP_HOST'];
	if($result){
		header('Location:'.$http.'/system/');
	}
}

?>

<!doctype html>
<html lang="zh-Hant">
  	<head>
		<?php include_once('./setup/head.php'); ?>
  	</head>
	
	<body style="align-items: center;width:100vw;">
		<h1 class="head" style="line-height:24vh;">致勝俱樂部</h1>
		<div class="container">
			<div class="row justify-content-center text-center" id="form_forget" style="margin:1vw;">
				<div style="height:10vh;margin-top:5vh;">
					<img src="/assets/img/forget.png" style="height:10vh;">
				</div>

			<div class="col-12" style="margin:15px 0;">
				<input type="email" v-model="user_data.email" class="form-control blackinput" placeholder="輸入電子信箱"style="background-color:transparent;color:#ffffff;border:5;" autofocus>
			</div>

			<div class="col-12" style="margin:4px 0;">
				<div class="input-group">
					<button class="btn" @click="btn_verify" style="background-color:transparent;color:#ffffff;border:5;" :disabled="btn_disabled">發送驗證碼</button>
					<input type="text" v-model="user_data.verifycode" class="form-control blackinput" maxlength="6"  placeholder="驗證碼[6位英數字]" style="background-color:transparent;color:#ffffff;border:5;">
				</div>
			</div>

			<div class="col-12" style="margin:5px 0;">
				<input type="password" v-model="user_data.pwd" class="form-control blackinput" placeholder="新密碼[6~15位英數字]" style="background-color:transparent;color:#ffffff;border:5;">
			</div>

			<div class="col-12" style="margin:5px 0;">
				<input type="password" v-model="user_data.rpwd" class="form-control blackinput" placeholder="新密碼驗證" style="background-color:transparent;color:#ffffff;border:5;">
			</div>

			<div class="col-11" style="margin:3vh 0;text-align:right;">
				<span style="color:#ffffff;" onclick="location.href='index.php';">立即登入</span>  
			</div>

			<div class="col-12" style="margin:3vh 0;text-align:center;">
				<button class="btn btn-lg btn-block picbtn" @click="btn_forget" style="background-image:url('/assets/img/go.png')"></button>
			</div>
		</div>
	
		<script>
			var form_forget = new Vue({
				el: '#form_forget',
				data: {
					user_data:{'email':'','pwd':'','rpwd':'','wpwd':'<?php echo $proxy_email; ?>','verifycode':''},
					Error_email:true,
					ErrMsg_email:'E-mail 格式錯誤',
					Error_pwd:true,
					ErrMsg_pwd:'密碼 格式錯誤',
                    btn_disabled:true
				},
				watch: {
					'user_data.email': function () {
						let val = this.user_data.email;
						var isMail = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/;
						if (!isMail.test(val)) {
							this.Error_email = true;
							this.ErrMsg_email = 'E-mail格式錯誤';
							this.btn_disabled = true;
						}
						else {
							this.Error_email = false;
							this.ErrMsg_email = 'E-mail格式正確';
							this.btn_disabled = false;
						}
					},
					'user_data.pwd':function () {
						let val = this.user_data.pwd;
						let val2 = this.user_data.rpwd;
						var isText = /^[a-zA-Z0-9]+$/;
						//var include = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,15}$/;
						if (!isText.test(val)) {
							this.Error_pwd = true;
							this.ErrMsg_pwd = '密碼 請勿包含特殊字元';
						}
						else if (val.length < 6) {
							this.Error_pwd = true;
							this.ErrMsg_pwd = '密碼 請勿少於6個字';
						}
						else if (val.length > 15) {
							this.Error_pwd = true;
							this.ErrMsg_pwd = '密碼 請勿超過15個字';
						}
						else if (val != val2) {
							this.Error_pwd = true;
							this.ErrMsg_pwd = '密碼 驗證錯誤!';
						}
						else {
							this.Error_pwd = false;
							this.ErrMsg_pwd = '密碼格式正確';
						}
					},
					'user_data.rpwd':function () {
						let val = this.user_data.rpwd;
						let val2 = this.user_data.pwd;
						var isText = /^[a-zA-Z0-9]+$/;
						//var include = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,15}$/;
						if (!isText.test(val)) {
							this.Error_pwd = true;
							this.ErrMsg_pwd = '密碼 請勿包含特殊字元';
						}
						else if (val.length < 6) {
							this.Error_pwd = true;
							this.ErrMsg_pwd = '密碼 請勿少於6個字';
						}
						else if (val.length > 15) {
							this.Error_pwd = true;
							this.ErrMsg_pwd = '密碼 請勿超過15個字';
						}
						else if (val != val2) {
							this.Error_pwd = true;
							this.ErrMsg_pwd = '密碼 驗證錯誤!';
						}
						else {
							this.Error_pwd = false;
							this.ErrMsg_pwd = '密碼格式正確';
						}
					}
				},
				methods: {
					btn_forget: function () {
						 if(this.Error_email){
							Swal.fire({ icon: 'error', title: this.ErrMsg_email });
						}else if(this.Error_pwd){
							Swal.fire({ icon: 'error', title: this.ErrMsg_pwd });
						}else if(this.Error_wpwd){
							Swal.fire({ icon: 'error', title: this.ErrMsg_wpwd });
						}else{
							axios.post('/api/forget.php', {
									switch: 'forget',
									data: this.user_data
								})
								.then(function (response) {
									if(response.data[0] == true){
										Swal.fire({ icon: 'success', title: response.data[1] });
										window.setTimeout(( () => location.href=('index.php') ), 800);
									}else{
										Swal.fire({ icon: 'error', title: response.data[1] });
									}
								})
								.catch(function (error) {
									//location.reload();
							});
						}
					},
					btn_verify: function () {
						if(this.Error_email){
							alert(this.ErrMsg_email);
						}else{
							this.btn_disabled = true;
							window.setTimeout(( () => form_forget.btn_disabled = false  ), 60000);
						
							axios.post('/api/forget.php', {
									switch: 'verify',
									data: this.user_data
								})
								.then(function (response) {
									var res = response.data;
									if(res[0] == true){
										//Swal.fire(res[1]);
									}else{
										//Swal.fire({ icon: 'error', title: res[1] });
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
