<!-- Alertify -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/alertify/alertify.min.css">

<script src="<?php echo base_url();?>assets/plugins/jQuery/jQuery-2.2.0.min.js"></script>
<form id="reset-form" method="POST">
	<label for="new_password">New Password :</label>
	<input type="password" name="new_password" id="new-password"/>
	<br/>
	<button id="btn-submit-reset">Reset</button>
</form>
<!-- Alertify -->
<script src="<?php echo base_url();?>assets/plugins/alertify/alertify.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/3.6.10/firebase.js"></script>
<script>
	$(function() {
		var $site_url = "<?php echo site_url();?>/";
		var $email = "";
		var $code = "";
		var $new_password = "";
		// Initialize Firebase
		var config = {
		apiKey: "AIzaSyD7ocO5EuIoanhpa3BCZtM-iV4Gw8IS2vs",
		authDomain: "minimediagnosis.firebaseapp.com",
		databaseURL: "https://minimediagnosis.firebaseio.com",
		storageBucket: "minimediagnosis.appspot.com",
		messagingSenderId: "460651139574"
		};
		firebase.initializeApp(config);
		
		$code = "<?php echo $_GET['oobCode'];?>";
		
		firebase.auth().verifyPasswordResetCode($code)
		.then(function(email) {
		  // Display a "new password" form with the user's email address
		  $email = email;
		})
		.catch(function() {
		  // Invalid code
		  $("#reset-form").hide();
		  alert("Maaf, link telah expired atau invalid");
		});
		
		
		
		$("#btn-submit-reset").click(function(){
			$new_password = $("#new-password").val();
			if($new_password == null || $new_password == ""){
				alert("Harap isi password baru");
				return false;
			}
			else{
				firebase.auth().confirmPasswordReset($code, $new_password)
				.then(function() {
				  // Success
				  // lanjut ganti ke php
				  
					var $data = {
						email : $email,
						new_password : $new_password,
					};
					
					$.ajax({
						url: $site_url+"LoginMobile/doResetPassword",
						data: $data,
						type: "POST",
						dataType: 'json',
						cache:false,
						success:function(data){
							if(data.status != "error"){
								alert("Selamat, anda telah berhasil mereset password anda !");
								$("#reset-form").hide();
							}else{
								alert("Terjadi kesalahan saat mereset password anda, harap coba lagi");
							}
						},
						error: function(xhr, status, error) {
							//var err = eval("(" + xhr.responseText + ")");
							//alertify.error(xhr.responseText);
							alert("Cannot response server !");
						}
					});
					
				})
				.catch(function() {
				  // Invalid code
				  alert("Maaf, link telah expired atau invalid");
				});
				return false;
			}
		});
	});
</script>