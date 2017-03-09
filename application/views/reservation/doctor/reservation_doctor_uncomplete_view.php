<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Mediagnosis | Reservation Doctor View</title>
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400" rel="stylesheet"> 
	<!-- Bootstrap 3.3.6 -->
  	<link rel="stylesheet" href="<?php echo base_url();?>assets/bootstrap/css/bootstrap.min.css">
	<!-- Theme style -->
  	<link rel="stylesheet" href="<?php echo base_url();?>assets/dist/css/AdminLTE.min.css">
  	<!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/font-awesome/css/font-awesome.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/ionicon/css/ionicons.min.css">
	<!-- Alertify -->
	<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/alertify/alertify.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/alertify/themes/default.min.css">

    <!-- jQuery 2.2.0 -->
    <script src="<?php echo base_url();?>assets/plugins/jQuery/jQuery-2.2.0.min.js"></script>
	<!-- Alertify -->
	<script src="<?php echo base_url();?>assets/plugins/alertify/alertify.min.js"></script>      
</head>
<style>
	body{
		background-image: url("<?php echo base_url("assets/img/reservation_bg.png");?>");
		background-repeat: no-repeat;
		background-size: cover;

	}

	div.header_doctor_reservation{
		display: block;
		position: relative;
		overflow: hidden;
		margin-bottom: 10%;
		margin-top: 3%;
	}

	div.welcome_message{
		font-family: 'Roboto', sans-serif;
		font-weight: 500;
		color: white;
		font-size: 25px;
		margin-left: 2%;
		float:left;
	}

	span.green{
		color:#20ba3c;
	}

	div.current_date{
		font-family: 'Roboto', sans-serif;
        font-size: 20px;
		color: white;
		float:right;
		margin-right: 2%;
	}

	.clear{
		clear:both;
	}

	.content_doctor_reservartion{
		display: block;
		color: white;
		font-family: 'Roboto', sans-serif;
		font-weight: 300;
		font-size: 16px;
    	position: relative;
    	bottom: 0;
    	margin-bottom: 2%;
	}

	.content_doctor_reservartion .next_patient{
		display: block;
		text-align: center;
        font-size: 24px;
	}

	.content_doctor_reservartion .patient_name{
		display: block;
		font-family: 'Roboto', sans-serif;
		font-weight: bold;
		color: black;
		font-size: 48px;
		text-align: center;
		margin : 10px;
	}

	.button_reservation{
		display: block;
		text-align: center;
		overflow: hidden;
	}

    .btn-reservation-confirmation{
        font-size: 20px;
    }
	.button_reservation .col-sm-2{
		border-radius: 20%;
	}
    /*Overlay*/
    .overlay{
        color:#fff;
    }
    .overlay .icon{
        font-size: 120px;
        text-align: center;
    }
</style>
<body>
    <div class="pull-right box-body">
        <a href="<?php echo site_url('Login/logout')?>" class="btn btn-lg btn-danger">Sign out</a>
    </div><div class="clear"></div>

	<div class="header_doctor_reservation">
		<div class="welcome_message">Selamat Datang, <span class="green"></span></div>
		<div class="current_date"><span id="date-name"></span>, pkl <span id="time-name"></span></div>
	</div>
	<div class="clear"></div>

    <div class="content_doctor_reservartion">
        <span class="next_patient">Maaf Akun Dokter Anda belum terhubung,</span>
		<span class="patient_name">
			Silahkan Hubungi Admin Klinik Anda untuk konfirmasi lebih lanjut..
		</span>
    </div>

    <script>
        $(function(){
            //DIGITAL ANALOG
            function dateTime(){
                var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                var myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                var date = new Date();
                var day = date.getDate();
                var month = date.getMonth();
                var thisDay = date.getDay(),
                    thisDay = myDays[thisDay];
                var yy = date.getYear();
                var year = (yy < 1000) ? yy + 1900 : yy;

                $("#date-name").html(thisDay + ', ' + day + ' ' + months[month] + ' ' + year);
            }

            function startTime() {
                var today=new Date(),
                    curr_hour=today.getHours(),
                    curr_min=today.getMinutes(),
                    curr_sec=today.getSeconds();
                curr_hour=checkTime(curr_hour);
                curr_min=checkTime(curr_min);
                curr_sec=checkTime(curr_sec);
                $("#time-name").html(curr_hour+":"+curr_min+":"+curr_sec);
            }
            function checkTime(i) {
                if (i<10) {
                    i="0" + i;
                }
                return i;
            }
            dateTime();
            setInterval(startTime, 500);

        });
    </script>
</body>
</html>