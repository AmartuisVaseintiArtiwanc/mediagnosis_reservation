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
		<div class="welcome_message">Selamat Datang, <span class="green"><?php echo $doctor_data->doctorName;?></span></div>
		<div class="current_date"><span id="date-name"></span>, pkl <span id="time-name"></span></div>
	</div>
	<div class="clear"></div>

    <div class="content_doctor_reservartion">
		<span class="next_patient">Pasien Anda Berikutnya : </span>
		<span class="patient_name">
			ALEX CANO (35)
		</span>

        <div class="hide">
            <input type="hidden" value="<?php echo $reversation_clinic_data->reservationID;?>" id="reservation-header-value">
            <input type="hidden" id="detail-reservation-value" value="0" />
            <input type="hidden" id="reservation-status" value="<?php echo $status;?>" />

            <div id="current-queue-info" data-queue=""></div>
            <audio id="loading-beep">
                <source src="../assets/custom/audio.mp3" type="audio/mp3"/>
            </audio>
        </div>
	</div>
	<div class="button_reservation">
		<div class="col-sm-4">
			&nbsp;
		</div>
		<div class="col-sm-2">
		    <a href="#" class="btn btn-sm btn-success btn-block btn-reservation-confirmation" data-value="confirm">
		        <i class="fa fa-check-circle"></i><b> Terima</b></a>
		</div>
		<div class="col-sm-2">
            <a href="#" class="btn btn-sm btn-danger btn-block btn-reservation-confirmation" data-value="reject">
                <i class="fa fa-remove"></i><b>Lewati</b></a>
        </div>
        <div class="col-sm-4">
			&nbsp;
		</div>
	</div>

    <div class="overlay" id="empty-queue-container">
        <div class="icon"><i class="fa fa-user-times"></i></div>
        <h2 class="text-center">TIDAK ADA ANTRIAN</h2>
    </div>

    <div class="overlay" id="waiting-queue-container">
        <div class="icon"><i class="fa fa-refresh fa-spin"></i></div>
        <h2 class="text-center">MENUNGGU KONFIRMASI ADMIN</h2>
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
	<script>
    $(function(){
        var count = 1;

        $(".content_doctor_reservartion").hide();
        $(".button_reservation").hide();
        $("#waiting-queue-container").hide();

        var $check_status = $("#reservation-status").val();

        if($check_status=='waiting'){
            $("#detail-reservation-value").val("<?php echo $detailID;?>");
            //SET COUNTER QUEUE
            $("#current-queue-info").attr("data-queue",2);
            //HIDE LOADING SCREEN
            $(".content_doctor_reservartion").hide();
            $(".button_reservation").hide();

            $("#waiting-queue-container").show();
            $("#empty-queue-container").hide();
        }

        var $detailID,$headerID;
        function getCurrentQueue() {
            var $base_url = "<?php echo site_url();?>/";
            var $currQueue = $("#current-queue-info").attr("data-queue");
            var $reservation = $("#reservation-header-value").val();
            if($currQueue == 0) {
                $.ajax({
                    url: $base_url+"reservationDoctor/getQueueCurrent",
                    data: {reservation : $reservation},
                    type: "POST",
                    dataType: 'json',
                    cache:false,
                    success:function(data){
                        if(data.status != "error"){
                            //RENDER QUEUE BOX
                            renderQueueBox(data.output['noQueue'],data.output['poliName'],data.output['patientName']);
                            //SET DATA RESERVATION
                            $detailID = data.output['detailID'];
                            $("#detail-reservation-value").val($detailID);
                            //SET COUNTER QUEUE
                            //$("#current-queue-info").attr("data-queue",1);

                            //HIDE LOADING SCREEN
                            $(".loading-screen-queue").hide();
                            alertSound();
                        }
                    },
                    error: function(xhr, status, error) {
                        //var err = eval("(" + xhr.responseText + ")");
                        //alertify.error(xhr.responseText);
                        //HIDE LOADING SCREEN
                        //alertify.error("Cannot response server ! Please Try Again");
                    }
                });
            }
        }
        setInterval(getCurrentQueue, 10000);

        setInterval(startMedicalRecord, 3000);
        function startMedicalRecord(){
            var $base_url = "<?php echo site_url();?>/";
            var $currQueue = $("#current-queue-info").attr("data-queue");
            var $detail_reservation = $("#detail-reservation-value").val();
            if($currQueue == 2) {
                $.ajax({
                    url: $base_url+"reservationDoctor/getStartQueueCurrent",
                    data: {detailReservation : $detail_reservation},
                    type: "POST",
                    dataType: 'json',
                    cache:false,
                    beforeSend:function(){
                        //SHOW LOADING SCREEN
                        $(".loading-screen-queue").removeClass("hide");
                        $(".loading-screen-queue").show();
                    },
                    success:function(data){
                        if(data.status == "success"){
                            alertSound();
                            //redirect to medical record
                            location.href = $base_url+"reservationDoctor/goToMedicalRecord/"+$detail_reservation;
                        }else if(data.status == "late"){
                            alertify.error(data.msg);
                            //HIDE LOADING SCREEN
                            $("#waiting-queue-container").hide();
                            $("#empty-queue-container").show();
                            //SET COUNTER QUEUE
                            $("#current-queue-info").attr("data-queue",0);
                        }else if(data.status == "error"){
                            //alertify.error(data.msg);
                            $(".loading-screen-queue").hide();
                        }
                    },
                    error: function(xhr, status, error) {
                        //var err = eval("(" + xhr.responseText + ")");
                        //alertify.error(xhr.responseText);
                        //HIDE LOADING SCREEN
                        //alertify.error("Cannot response server ! Please Try Again");
                    }
                });
            }
        }

        function renderQueueBox(q_number,poli_name,patient_name){
            $(".patient_name").html(patient_name);
            $(".content_doctor_reservartion").show();
            $(".button_reservation").show();
            $("#empty-queue-container").hide();
        }

        function alertSound(){
            var audio = $("#loading-beep")[0];
            audio.play();
        }

        // CONFIRM ANTRIAN SEKARANG
        $(".btn-reservation-confirmation").click(function(){
            var $value = $(this).attr("data-value");
            var $reservation = $("#reservation-header-value").val();
            var detailID = $("#detail-reservation-value").val();

            if($value=="confirm"){
                $msg="Apakah Anda yakin untuk menerima pasien ini ?";
                $data = {
                    headerID : $reservation,
                    detailID : detailID
                };
                saveConfirmReservation($data,$msg);
            }else if($value=="reject"){
                $msg="Apakah Anda yakin untuk melewati pasien ini ?";
                alertify.confirm($msg,
                    function() {
                        //SET COUNTER QUEUE
                        $("#current-queue-info").attr("data-queue",0);
                        //REMOVE BOX
                        //HIDE LOADING SCREEN
                        $(".content_doctor_reservartion").hide();
                        $(".button_reservation").hide();
                        $("#empty-queue-container").show();

                    }
                ).setHeader("KONFIRMASI");

            }

        });

        function saveConfirmReservation($data,$msg){
            var $title = "KONFIRMASI";
            var $base_url = "<?php echo site_url();?>/";

            alertify.confirm($msg,
                function(){
                    $.ajax({
                        url: $base_url+"reservationDoctor/saveCurrentQueue",
                        data: $data,
                        type: "POST",
                        dataType: 'json',
                        cache:false,
                        beforeSend:function(){
                            //SHOW LOADING SCREEN
                            $(".loading-screen-queue").removeClass("hide");
                            $(".loading-screen-queue").show();
                        },
                        success:function(data){
                            if(data.status == "success"){
                                alertify.success(data.msg);
                                //SET COUNTER QUEUE to 2 (CHECK ADMIN CONFIRMATION)
                                $("#current-queue-info").attr("data-queue",2);
                                //HIDE LOADING SCREEN
                                $(".content_doctor_reservartion").hide();
                                $(".button_reservation").hide();

                                $("#waiting-queue-container").show();
                                $("#empty-queue-container").hide();

                            }else if(data.status == "taken"){
                                alertify.error(data.msg);
                                //SET COUNTER QUEUE
                                $("#current-queue-info").attr("data-queue",0);
                                //REMOVE BOX
                                //HIDE LOADING SCREEN
                                $(".content_doctor_reservartion").hide();
                                $(".button_reservation").hide();
                                $("#empty-queue-container").show();

                            }else if(data.status == "error"){
                                alertify.error(data.msg);
                            }
                        },
                        error: function(xhr, status, error) {
                            //var err = eval("(" + xhr.responseText + ")");
                            //alertify.error(xhr.responseText);
                            //HIDE LOADING SCREEN
                            alertify.error("Cannot response server ! Please Try Again");
                        }
                    });
                }
            ).setHeader($title);
        }
    });
</script>
</body>
</html>