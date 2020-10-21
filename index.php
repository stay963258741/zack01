<!doctype html>
<html lang="zh-Hant">
  	<head>
		<?php include_once('./setup/head.php'); ?>
	</head>
	<body style="align-items: center;width:100vw;">
		<h1 class="head" style="line-height:30vh;">致勝俱樂部</h1>
		<div class="container">
			<div class="row justify-content-center text-center" id="form_login">
				<div class="col-12" style="margin:2vh 0;">
					<input type="email" v-model="user_data.email" class="form-control blackinput" placeholder="輸入信箱或電話號碼" style="background-color:#70707099;color:#ffffff;border:0;">
				</div>
				<div class="col-12" style="margin:2vh 0;">
					<input type="password" v-model="user_data.pwd" class="form-control blackinput" placeholder="密碼" style="background-color:#70707099;color:#ffffff;border:0;">
				</div>
				<div class="col-12" style="margin:5vh 0;">
					<button class="btn btn-lg btn-block picbtn" @click="btn_login" style="background-image:url('/assets/img/enter.png');"></button>
				</div>
				<div class="col-12" style="margin:5vh 0;">
					<button class="btn btn-lg btn-block picbtn" onclick="location.href='register.php';" style="background-image:url('/assets/img/registered.png');"></button>
					<button class="btn btn-lg btn-block picbtn" onclick="location.href='forget.php';" style="background-image:url('/assets/img/forget.png');"></button>
				</div>
			</div>
		</div>
		<script>
			var form_login = new Vue({
				el: '#form_login',
				data: {
					user_data:{'email':'','pwd':''},
					Error_email:true,
					ErrMsg_email:'E-mail格式錯誤',
					Error_pwd:true,
					ErrMsg_pwd:'密碼格式錯誤',
				},
				mounted() {
					if(localStorage.mgb_email){
						this.user_data.email = localStorage.mgb_email;
					}
				},
				watch: {
					'user_data.email': function () {
						let val = this.user_data.email;
						var isMail = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/;
						if (!isMail.test(val)) {
							this.Error_email = true;
							this.ErrMsg_email = 'E-mail格式錯誤';
						}
						else {
							this.Error_email = false;
							this.ErrMsg_email = 'E-mail格式正確';
						}
					},
					'user_data.pwd':function () {
						let val = this.user_data.pwd;
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
						else {
							this.Error_pwd = false;
							this.ErrMsg_pwd = '密碼格式正確';
						}
					}
				},
				methods: {
					btn_login: function () {
						if(this.Error_email){
							Swal.fire({ icon: 'error', title: this.ErrMsg_email });
						}else if(this.Error_pwd){
							Swal.fire({ icon: 'error', title: this.ErrMsg_pwd });
						}else{
							localStorage.mgb_email = this.user_data.email;
							axios.post('/api/index.php', {
									data: this.user_data
								})
								.then(function (response) {
									if(response.data[0] == true){
										location.reload();
									}else{
										Swal.fire({ icon: 'error', title: response.data[1] });
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


