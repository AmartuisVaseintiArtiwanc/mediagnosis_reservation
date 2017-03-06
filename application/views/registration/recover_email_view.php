<script src="<?php echo base_url();?>assets/plugins/jQuery/jQuery-2.2.0.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/3.6.10/firebase.js"></script>
<script>
	$(function() {
		var $site_url = "<?php echo site_url();?>/";
		var $code = "";
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
		var $restoredEmail = null;
		// Confirm the action code is valid.
		firebase.auth().checkActionCode($code).then(function(info) {
			// Get the restored email address.
			$restoredEmail = info['data']['email'];
			$nowEmail = info['data']['fromEmail'];

			// Revert to the old email.
			return firebase.auth().applyActionCode($code);
		}).then(function() {
				// Account email reverted to $restoredEmail
				//change server email too
				var $data = {
					oldEmail : $restoredEmail,
					newEmail : $nowEmail,
				};
				
				$.ajax({
					url: $site_url+"LoginMobile/doRevertEmail",
					data: $data,
					type: "POST",
					dataType: 'json',
					cache:false,
					success:function(data){
						if(data.status != "error"){
							// TODO: Display a confirmation message to the user.
							var isResetPass = confirm("Email anda telah di kembalikan ke email yang lama. Apakah anda ingin melakukan reset password sekalian ?");
							if(isResetPass == true){
								// You might also want to give the user the option to reset their password
								// in case the account was compromised:
								firebase.auth().sendPasswordResetEmail($restoredEmail).then(function() {
									// Password reset confirmation sent. Ask user to check their email.
									alert("Terima kasih. Silahkan cek email anda untuk melakukan reset password");
								}).catch(function(error) {
									// Error encountered while sending password reset code.
									alert("Maaf, terjadi kesalahan saat mengirim email reset password. Harap coba lagi");
								});
							}else{
								alert("Terima kasih. Silahkan login dengan email lama anda");
							}
						}else{
							alert("Terjadi kesalahan saat melakukan revert email anda, harap coba lagi");
						}
					},
					error: function(xhr, status, error) {
						//var err = eval("(" + xhr.responseText + ")");
						//alertify.error(xhr.responseText);
						alert("Cannot response server !");
					}
				});
				
			
		}).catch(function(error) {
		// Invalid code.
		alert("Maaf, link telah expired atau invalid");
		});


	});
</script>