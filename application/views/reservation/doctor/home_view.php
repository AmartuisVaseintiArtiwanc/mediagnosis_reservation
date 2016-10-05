<style>
    #current-queue-box{
        min-height: 180px;
    }
    .small-box>.inner {
        padding: 10px;
    }
    .small-box .icon {
        top:10px;
        right: 20px;
    }
    .small-box h3{
        font-size: 40px;
    }
    .small-box p{
        font-size: 20px;
    }
    #button-confirm-queue{
        padding-top: 10px;
    }

    /*Small box list*/
    .small-box-list{
        border-radius: 2px;
        position: relative;
        display: block;
        margin-bottom: 10px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    }
    .small-box-list>.inner{
        padding: 5px;
        padding-left: 20px;
    }
    .small-box-list .icon {
        top:12px;
        right: 20px;
        -webkit-transition: all .3s linear;
        -o-transition: all .3s linear;
        transition: all .3s linear;
        position: absolute;
        z-index: 0;
        font-size: 60px;
        color: rgba(0,0,0,0.15);
    }
    .small-box-list h3{
        font-size: 30px;
        font-weight: bold;
        margin: 5px 0 1px 0;
        white-space: nowrap;
        padding: 0;
    }
    .small-box-list p{
        font-size: 16px;
    }

    /*Overlay*/
    .overlay h3{
        top: 57px;
        position: relative;
    }
    .test{
        /* background: #1a86b9; */
        background: -moz-linear-gradient(80deg, #1a86b9 51%, #0078b1 51%);
        /* background: -webkit-gradient(linear, left bottom, right top, color-stop(51%,#1a86b9), color-stop(51%,#0078b1)); */
        /* background: -webkit-linear-gradient(80deg, #1a86b9 51%,#0078b1 51%); */
        background: -o-linear-gradient(80deg, #1a86b9 51%,#0078b1 51%);
        background: -ms-linear-gradient(80deg, #1a86b9 51%,#0078b1 51%);
        background: linear-gradient(80deg, #1a86b9 51%,#0078b1 51%);
    }

</style>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Reservation
        <small>Clinic</small>

    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Reservation</a></li>
        <li class="active">Clinic</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
                <i class="fa fa-hospital-o"></i> <?php echo $reversation_clinic_data->clinicName;?> - <?php echo date("l, j F Y");?>
                <input type="hidden" value="<?php echo $reversation_clinic_data->reservationID;?>" id="reservation-header-value">
            </h2>
        </div>
        <!-- /.col -->
    </div>
    <div class="row">

        <div class="col-lg-6">
            <div class="box" id="content-container" >
                <div class="box-header">
                    <h3 class="box-title">Antrian Berikutnya</h3>
                </div>

                <div class="box-body">
                    <div class="box box-primary">
                        <div class="box-body box-profile" id="current-queue-box" data-queue="0">
                            <div class="row hide" id="button-confirm-queue">
                                <div class="col-lg-6">
                                    <a href="#" class="btn btn-lg btn-danger btn-block btn-reservation-confirmation" data-value="reject">
                                        <i class="fa fa-remove"></i><b>LEWATI</b></a>
                                </div>
                                <div class="col-lg-6">
                                    <a href="#" class="btn btn-lg btn-success btn-block btn-reservation-confirmation" data-value="confirm">
                                        <i class="fa fa-check-circle"></i><b> TERIMA</b></a>
                                </div>
                            </div>

                            <input type="hidden" id="detail-reservation-value" value="0" />
                            <div id="current-queue-info" data-queue-number="" data-queue-poli="" data-queue-doctor=""></div>

                            <div class="overlay loading-screen-queue">
                                <i class="fa fa-user-times"></i>
                                <br/>
                                <h3 class="text-center">TIDAK ADA ANTRIAN</h3>
                            </div>
                        </div>

                        <div class="hide">
                            <audio id="loading-beep">
                                <source src="../assets/custom/audio.mp3" type="audio/mp3"/>
                            </audio>

                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<script>
    $(function(){
        var count = 1;

        var $detailID,$headerID;
        function getCurrentQueue() {
            var $base_url = "<?php echo site_url();?>/";
            var $currQueue = $("#current-queue-box").attr("data-queue");
            var $reservation = $("#reservation-header-value").val();
            if($currQueue == 0) {
                $.ajax({
                    url: $base_url+"reservationDoctor/getQueueCurrent",
                    data: {reservation : $reservation},
                    type: "POST",
                    dataType: 'json',
                    cache:false,
                    beforeSend:function(){
                        //SHOW LOADING SCREEN
                        $(".loading-screen-queue").removeClass("hide");
                        $(".loading-screen-queue").show();
                    },
                    success:function(data){
                        if(data.status != "error"){
                            //RENDER QUEUE BOX
                            renderQueueBox(data.output['noQueue'],data.output['poliName'],data.output['patientName']);
                            //SET DATA RESERVATION
                            $detailID = data.output['detailID'];
                            $("#detail-reservation-value").val($detailID);
                            //SET COUNTER QUEUE
                            $("#current-queue-box").attr("data-queue",1);

                            //HIDE LOADING SCREEN
                            $(".loading-screen-queue").hide();
                            alertSound();
                        }
                    },
                    error: function(xhr, status, error) {
                        //var err = eval("(" + xhr.responseText + ")");
                        //alertify.error(xhr.responseText);
                        //HIDE LOADING SCREEN
                        $(".loading-screen-queue").hide();
                    }
                });
            }
        }
        setInterval(getCurrentQueue, 1000);

        setInterval(startMedicalRecord, 1000);
        function startMedicalRecord(){
            var $base_url = "<?php echo site_url();?>/";
            var $currQueue = $("#current-queue-box").attr("data-queue");
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
                            //redirect to medical record
                            location.href = $base_url+"reservationDoctor/goToMedicalRecord/"+$detail_reservation;
                        }else if(data.status == "late"){
                            alertify.error(data.msg);
                            $(".loading-screen-queue").hide();
                            //SET COUNTER QUEUE
                            $("#current-queue-box").attr("data-queue",0);
                            //REMOVE BOX
                            $("#current-queue-box").children(".small-box").html("");
                            $("#button-confirm-queue").hide();
                        }else if(data.status == "error"){
                            //alertify.error(data.msg);
                            $(".loading-screen-queue").hide();
                        }
                    },
                    error: function(xhr, status, error) {
                        //var err = eval("(" + xhr.responseText + ")");
                        //alertify.error(xhr.responseText);
                        //HIDE LOADING SCREEN
                        $(".loading-screen-queue").hide();
                    }
                });
            }
        }

        function renderQueueBox(q_number,poli_name,patient_name){
            var $small_box = $("<div>", {class: "small-box bg-green", "data-value": "0"});
            var $inner = $("<div>", {class: "inner", "data-value": "0"});
            var $queue_number = $("<h3>").html(q_number+" - "+patient_name);
            var $poli_doctor = $("<p>").html(poli_name);

            var $icon = $("<div>", {class: "icon"});
            var $i = $("<i>", {class: "ion ion-person"});
            $i.appendTo($icon)

            $queue_number.appendTo($inner);
            $poli_doctor.appendTo($inner);
            $inner.appendTo($small_box);
            $icon.appendTo($small_box)
            $("#current-queue-box").prepend($small_box);

            $("#current-queue-info").attr("data-queue-number",q_number);
            $("#current-queue-info").attr("data-queue-poli",poli_name);

            $("#button-confirm-queue").removeClass("hide");
            $("#button-confirm-queue").show();
        }

        function alertSound(){
            var audio = $("#loading-beep")[0];
            audio.play();
        }

        // CONFIRM ANTRIAN SEKARANG
        $(".btn-reservation-confirmation").click(function(){
            var $value = $(this).attr("data-value");
            var $title = "Confirmation";
            var $msg = "";
            var $reservation = $("#reservation-header-value").val();
            var detailID = $("#detail-reservation-value").val();
            var $base_url = "<?php echo site_url();?>/";

            if($value=="confirm"){
                $msg="Anda yakin untuk memeriksa pasien ini ?";
                $data = {
                    headerID : $reservation,
                    detailID : detailID
                };
            }else if($value=="reject"){
                $msg="Anda yakin untuk tidak memeriksa pasien ini ?";
                $data = {
                    headerID : $reservation,
                    detailID : detailID
                };
            }

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
                                $("#current-queue-box").attr("data-queue",2);
                                //HIDE LOADING SCREEN
                                $(".loading-screen-queue").hide();

                                //REMOVE BOX
                                $("#current-queue-box").children(".small-box").html("");
                                $("#button-confirm-queue").hide();

                            }else if(data.status == "taken"){
                                alertify.error(data.msg);
                                $(".loading-screen-queue").hide();
                                //SET COUNTER QUEUE
                                $("#current-queue-box").attr("data-queue",0);
                                //REMOVE BOX
                                $("#current-queue-box").children(".small-box").html("");
                                $("#button-confirm-queue").hide();

                            }else if(data.status == "error"){
                                alertify.error(data.msg);
                                $(".loading-screen-queue").hide();
                            }
                        },
                        error: function(xhr, status, error) {
                            //var err = eval("(" + xhr.responseText + ")");
                            //alertify.error(xhr.responseText);
                            //HIDE LOADING SCREEN
                            alertify.error("Cannot response server !");
                            $(".loading-screen-queue").hide();
                        }
                    });
                }
            ).setHeader($title);
        });
    });
</script>


