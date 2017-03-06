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
		// Try to apply the email verification code.
		firebase.auth().applyActionCode($code).then(function(resp) {
		// Email address has been verified.

		// TODO: Display a confirmation message to the user.
		// You could also provide the user with a link back to the app.
		alert("Email anda telah terverifikasi. Terima kasih.");
		}).catch(function(error) {
		// Code is invalid or expired. Ask the user to verify their email address
		// again.
		alert("Maaf, link telah expired atau invalid");
		});

	});
</script>