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

$proxy_uid='20001500';   /*如果沒有推薦碼，自動代入管理員推薦碼*/

if(isset($_GET['p'])){
	$proxy_md5 = $_GET['p'];
	include_once("api/sql.php");
	$conn = new PDO("mysql:host=".constant("_server").";dbname=".constant("_db"), constant("_acc"), constant("_pwd"));
	$sth_check = $conn->prepare("SELECT * FROM `user_info` WHERE `proxy_md5`=?");
	$sth_check->execute(array($proxy_md5));
	$count_check = $sth_check->rowCount();
	if($count_check == 1){
		$game_info = $sth_check->fetchAll(PDO::FETCH_ASSOC)[0];
		$proxy_uid=$game_info['uid'];
	}
}

?>

<!doctype html>
<html lang="zh-Hant">
  	<head>
		<?php include_once('./setup/head.php'); ?>
	</head>
	<body style="align-items: center;width:100vw;">
		<h1 class="head" style="line-height:10vh;">致勝俱樂部</h1>
		<div class="container">
			<div class="row justify-content-center text-center" id="form_register" style="margin:1vw;">
				<div style="height:10vh;">
					<img src="/assets/img/registered.png" style="height:10vh;">
				</div>

				<div class="col-12" style="margin:2px 0;">
					<input type="text" v-model="user_data.name" class="form-control blackinput" placeholder="請輸入姓名" style="background-color:transparent;color:#ffffff;border:5;" autofocus>
				</div>

				<div class="col-12" style="margin:2px 0;">
					<input type="text" v-model="user_data.phone" class="form-control blackinput" placeholder="請輸入電話號碼" style="background-color:transparent;color:#ffffff;border:5;" autofocus>
				</div>

				<div class="col-12" style="margin:2px 0;">
					<input type="email" v-model="user_data.email" class="form-control blackinput" placeholder="電子信箱" style="background-color:transparent;color:#ffffff;border:5;">
				</div>

				<div class="col-12" style="margin:2px 0;">
					<div class="input-group">
                        <button class="btn" @click="btn_verify" style="background-color:transparent;color:#ffffff;border:5;" :disabled="btn_disabled">發送驗證碼</button>
						<input type="text" v-model="user_data.verifycode" class="form-control blackinput" maxlength="6"  placeholder="驗證碼[6位英數字]" style="background-color:transparent;color:#ffffff;border:5;">
					</div>
				</div>

				<div class="col-12" style="margin:2px 0;">
					<input type="password" v-model="user_data.pwd" class="form-control blackinput" placeholder="密碼[6~15位英數字]" style="background-color:transparent;color:#ffffff;border:5;">
                </div>

				<div class="col-12" style="margin:2px 0;">
					<input type="password" v-model="user_data.rpwd" class="form-control blackinput" placeholder="密碼驗證" style="background-color:transparent;color:#ffffff;border:5;">
                </div>
				
				<div class="col-12" style="margin:2px 0;">
					<input type="text" readonly v-model="user_data.proxy" class="form-control blackinput" placeholder="邀請人UID" style="background-color:transparent;color:#ffffff;border:5;">
                </div>			 <!--  readonly 此欄位只能讀不能改，所有欄位適用 -->

				<div class="col-12" style="margin:2px 0;">
					<select v-model="user_data.address" class="form-control blackinput" style="background-color:transparent;color:#ffffff;" autofocus>
						<option value="地區"  style="background-color:black;">請選擇地區</option>
						<option value="台灣" style="background-color:black;">台灣</option>
						<option value="香港" style="background-color:black;">香港</option>
						<option value="澳門" style="background-color:black;">澳門</option>
						<option value="中國" style="background-color:black;">中國</option>
						<option value="新加玻" style="background-color:black;">新加坡</option>
						<option value="馬來西亞" style="background-color:black;">馬來西亞</option>
					</select>
				</div>

				<div class="col-12" style="margin:2px 0;">
					<select v-model="user_data.interest" class="form-control blackinput" style="background-color:transparent;color:#ffffff;" autofocus>
						<option value="興趣"  style="background-color:black;">請選擇興趣</option>
						<option value="興趣1" style="background-color:black;">興趣1</option>
						<option value="興趣2" style="background-color:black;">興趣2</option>
						<option value="興趣3" style="background-color:black;">興趣3</option>
						<option value="興趣4" style="background-color:black;">興趣4</option>
					</select>
				</div>
	
				<div class="col-12" style="margin:3vh 0;text-align:right;">
					<span style="color:#ffffff;" onclick="location.href='index.php';">立即登入</span>  
				</div>

				<div class="col-12" style="margin-buttom:6vh 0;text-align:center;">
					<button class="btn btn-lg btn-block picbtn" @click="btn_register" style="background-image:url('/assets/img/check.png');"></button>
				</div>
				
			</div>
		</dvi>
		<script>
			var form_register = new Vue({
				el: '#form_register',
				data: {
					user_data:{'name':'','phone':'','interest':'興趣','email':'','address':'地區','pwd':'','rpwd':'','proxy':'<?php echo $proxy_uid; ?>','verifycode':''},
					Error_name:true,
					ErrMsg_name:'姓名 格式錯誤',
					Error_phone:true,
					ErrMsg_phone:'電話號碼 格式錯誤',
					Error_interest:true,
					ErrMsg_interest:'未選擇興趣',
					Error_address:true,
					ErrMsg_address:'未選擇地區',
					Error_email:true,
					ErrMsg_email:'E-mail 格式錯誤',
					Error_pwd:true,
					ErrMsg_pwd:'密碼 格式錯誤',
					Error_proxy:false,
					ErrMsg_proxy:'請輸入8碼 推薦人UID',
					btn_disabled:true,
					
				},
				watch: {
					'user_data.name': function () {
						let val = this.user_data.name;
						if (val.length < 2) {
							this.Error_name = true;
							this.ErrMsg_name = '姓名 請勿少於2個字';
						}
						else if (val.length > 10) {
							this.Error_name = true;
							this.ErrMsg_name = '姓名 請勿超過10個字';
						}
						else {
							this.Error_name = false;
							this.ErrMsg_name = '姓名 格式正確';
						}
					},
					'user_data.phone': function () {
						let val = this.user_data.phone;
						if (val.length < 9) {
							this.Error_phone = true;
							this.ErrMsg_phone = '電話號碼 請勿少於10個字';
						}
						else if (val.length > 10) {
							this.Error_phone = true;
							this.ErrMsg_phone = '電話號碼 請勿超過10個字';
						}
						else {
							this.Error_phone = false;
							this.ErrMsg_phone = '電話號碼 格式正確';
						}
					},
					'user_data.interest': function () {
						let val = this.user_data.interest;
						if (val == '興趣' ) {
							this.Error_interest = true;
							this.ErrMsg_interest = '未選擇興趣';
						}
						else {
							this.Error_interest = false;
							this.ErrMsg_interest = '已選擇興趣';
						}
					},
					'user_data.address': function () {
						let val = this.user_data.address;
						if (val == '地區' ) {
							this.Error_address = true;
							this.ErrMsg_address = '未選擇地區';
						}
						else {
							this.Error_address = false;
							this.ErrMsg_address = '已選擇地區';
						}
					},
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
					},
					'user_data.proxy': function () {
						let val = this.user_data.proxy;
						var isText = /^[0-9]+$/;
						//var include = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,15}$/;
						if (!isText.test(val)) {
							this.Error_proxy = false;
							this.ErrMsg_proxy = '請輸入8碼 邀請人UID';
						}
						else if (val.length != 8) {
							this.Error_proxy = false;
							this.ErrMsg_proxy = '請輸入8碼 邀請人UID';
						}else{
							this.Error_proxy = false;
							this.ErrMsg_proxy = '邀請人UID正確';
						}
					},
				},
				methods: {
					btn_register: function () {
						if(this.Error_name){
							Swal.fire({ icon: 'error', title: this.ErrMsg_name });
						}else if(this.Error_phone){
							Swal.fire({ icon: 'error', title: this.ErrMsg_phone });
						}else if(this.Error_interest){
							Swal.fire({ icon: 'error', title: this.ErrMsg_interest });
						}else if(this.Error_address){
							Swal.fire({ icon: 'error', title: this.ErrMsg_address });
						}else if(this.Error_email){
							Swal.fire({ icon: 'error', title: this.ErrMsg_email });
						}else if(this.Error_pwd){
							Swal.fire({ icon: 'error', title: this.ErrMsg_pwd });
						}else{
							axios.post('/api/register.php', {
									switch: 'register',
									data: this.user_data
								})
								.then(function (response) {
									if(response.data[0] == true){
										Swal.fire({ icon: 'success', title: response.data[1] });
										window.setTimeout(( () => location.href=('index.php') ), 1000);
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
							window.setTimeout(( () => form_register.btn_disabled = false  ), 60000);
						
							axios.post('/api/register.php', {
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
