<style>
    .current-queue-box{
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

    #next-queue-box-list{
        max-height: 600px;
        overflow-x: scroll;
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
                <?php if($this->session->userdata('role')=="super_admin"){?>
                    <a href="<?=site_url('Reservation/index')?>">
                        <button class="btn btn-primary pull-right" type="button">
                            <span class="glyphicon glyphicon-circle-arrow-left"></span> Back to list
                        </button>
                    </a>
                <?php } ?>
                <input type="hidden" value="<?php echo $reversation_clinic_data->clinicID;?>" id="clinic-header-value">
            </h2>
        </div>
        <!-- /.col -->
    </div>
    <div class="row">

        <div class="col-lg-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Antrian Berikutnya</h3>
                </div>

                <div class="box-body" id="next-queue-box-list">
                    <?php foreach($reservation_latest_queue as $row) { ?>
                        <div class="col-lg-12 col-xs-12" id="next-queue-<?php echo $row['detailReservationID'];?>">
                            <div class="small-box-list bg-green">
                                <div class="inner">
                                    <h3><?php echo $row['noQueue'];?> - <?php echo $row['patientName'];?></h3>
                                    <p><?php echo strtoupper($row['poliName']);?></p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person"></i>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <?php foreach($poli_list as $row){?>
                <div class="box box-poli" data-poli="<?php echo $row['poliID'];?>">
                    <div class="box-header">
                        <h3 class="box-title"><?php echo strtoupper($row['poliName']);?></h3>
                    </div>
                    <div class="box-body">
                        <div class="box box-primary">
                            <div class="box-body box-profile current-queue-box" id="current-queue-box-<?php echo $row['poliID'];?>" data-queue="0">
                                <div class="row hide" id="button-confirm-queue-<?php echo $row['poliID'];?>">
                                    <div class="col-lg-6">
                                        <a href="#" class="btn btn-lg btn-danger btn-block btn-reservation-confirmation" data-poli="<?php echo $row['poliID'];?>" data-value="reject">
                                            <i class="fa fa-remove"></i><b> TIDAK ADA</b></a>
                                    </div>
                                    <div class="col-lg-6">
                                        <a href="#" class="btn btn-lg btn-success btn-block btn-reservation-confirmation" data-poli="<?php echo $row['poliID'];?>" data-value="confirm">
                                            <i class="fa fa-check-circle"></i><b> ADA</b></a>
                                    </div>
                                </div>

                                <input type="hidden" id="detail-reservation-value-<?php echo $row['poliID'];?>" value="0" />
                                <div id="current-queue-info-<?php echo $row['poliID'];?>" data-queue-number="" data-queue-poli="" data-queue-doctor=""></div>

                                <div class="overlay loading-screen-queue" id="loading-screen-queue-<?php echo $row['poliID'];?>">
                                    <i class="fa fa-user-times"></i>
                                    <br/>
                                    <h3 class="text-center">TIDAK ADA ANTRIAN</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <!-- /.box-body -->
            <div class="hide">
                <audio id="loading-beep">
                    <source src="<?php echo base_url();?>/assets/custom/audio.mp3" type="audio/mp3"/>
                </audio>

            </div>
        </div>
    </div>
</section>

<script>
    $(function(){
        var count = 1;
        var $detailID,$headerID;
        var $poliList = [];

        getPoliList();
        function getPoliList(){
            $('.box-poli').each(function(){
                var $poli= $(this).attr("data-poli");
                $poliList.push($poli);
            });
        }
        function getCurrentQueueEachPoli(poli){
            var $base_url = "<?php echo site_url();?>/";
            var $currQueue = $("#current-queue-box-"+poli).attr("data-queue");
            var $clinic = $("#clinic-header-value").val();
            if($currQueue == 0) {
                $.ajax({
                    url: $base_url+"reservation/getQueueCurrent",
                    data: {clinic : $clinic, poli : poli},
                    type: "POST",
                    dataType: 'json',
                    cache:false,
                    beforeSend:function(){
                        //SHOW LOADING SCREEN
                        $("#loading-screen-queue-"+poli).removeClass("hide");
                        $("#loading-screen-queue-"+poli).show();
                    },
                    success:function(data){
                        if(data.status != "error"){
                            //RENDER QUEUE BOX
                            if(data.output['poliID'] == poli){
                                renderQueueBox(data.output['noQueue'],data.output['poliName'],data.output['doctorName'], data.output['patientName'],data.output['poliID']);
                                //SET DATA RESERVATION
                                $detailID = data.output['detailID'];
                                $poliID = data.output['poliID'];
                                $("#detail-reservation-value-"+$poliID).val($detailID);
                                //SET COUNTER QUEUE
                                $("#current-queue-box-"+poli).attr("data-queue",1);
                                // REMOVE NEXT QUEUE ON LIST
                                //$("#next-queue-"+$detailID).remove();
                                //HIDE LOADING SCREEN
                                $("#loading-screen-queue-"+poli).hide();
                                alertSound();
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        //var err = eval("(" + xhr.responseText + ")");
                        //alertify.error(xhr.responseText);
                        //HIDE LOADING SCREEN
                        $("#loading-screen-queue-"+poli).hide();
                    }
                });
            }
        }

        function loopGetCurrentQuery(){
            $.each($poliList, function( index, value ) {
                getCurrentQueueEachPoli(value);
            });

            getNextQueueList();
        }

        setInterval(loopGetCurrentQuery, 3000);

        function renderQueueBox(q_number,poli_name, doctor_name, patient_name, poli){
            var $small_box = $("<div>", {class: "small-box bg-green", "data-value": "0"});
            var $inner = $("<div>", {class: "inner", "data-value": "0"});
            var $queue_number = $("<h3>", {class: "text-center"}).html(q_number+" - "+patient_name);
            var $poli_doctor = $("<p>", {class: "text-center"}).html(poli_name+" - "+doctor_name);

            $queue_number.appendTo($inner);
            $poli_doctor.appendTo($inner);
            $inner.appendTo($small_box);
            $("#current-queue-box-"+poli).prepend($small_box);

            $("#current-queue-info-"+poli).attr("data-queue-number",q_number);
            $("#current-queue-info-"+poli).attr("data-queue-poli",poli_name);
            $("#current-queue-info-"+poli).attr("data-queue-doctor",doctor_name);

            $("#button-confirm-queue-"+poli).removeClass("hide");
            $("#button-confirm-queue-"+poli).show();
        }

        function alertSound(){
            var audio = $("#loading-beep")[0];
            audio.play();
        }

        // CONFIRM ANTRIAN SEKARANG
        $(".btn-reservation-confirmation").click(function(){
            var $value = $(this).attr("data-value");
            var $poli = $(this).attr("data-poli");

            var $msg = "";
            var $detailID = $("#detail-reservation-value-"+$poli).val();

            if($value=="confirm"){
                $msg="Pasien Ada ?";
                $data = {
                    status : "examine",
                    detailID : $detailID
                };
                confirmReservation($msg, $detailID, $poli);
            }else if($value=="reject"){
                $msg="Pasien Tidak Ada ?";
                $data = {
                    status : "late",
                    detailID : $detailID
                };
                rejectReservation($msg, $data, $poli);
            }
        });

        //REJECT ANTRIAN SEKARANG
        function confirmReservation($msg, $detailID, $poli){
            var $title = "Konfirmasi";
            var $base_url = "<?php echo site_url();?>/";
            alertify.confirm($msg,
                function(){
                    //SET COUNTER QUEUE
                    $("#current-queue-box-"+$poli).attr("data-queue",0);
                    //HIDE LOADING SCREEN
                    $("#loading-screen-queue-"+$poli).hide();

                    //REMOVE BOX
                    $("#current-queue-box-"+$poli).children(".small-box").html("");
                    $("#button-confirm-queue-"+$poli).hide();
                    window.open($base_url+"Reservation/goToPhysicalExamination/"+$detailID);
                }
            ).setHeader($title);
        }

        function rejectReservation($msg, $data, $poli){
            var $title = "Konfirmasi";
            var $base_url = "<?php echo site_url();?>/";
            alertify.confirm($msg,
                function(){
                    $.ajax({
                        url: $base_url+"Reservation/saveCurrentQueue",
                        data: $data,
                        type: "POST",
                        dataType: 'json',
                        cache:false,
                        beforeSend:function(){
                            //SHOW LOADING SCREEN
                            $("#loading-screen-queue-"+$poli).removeClass("hide");
                            $("#loading-screen-queue-"+$poli).show();
                        },
                        success:function(data){
                            if(data.status != "error"){
                                alertify.success(data.msg);
                                //SET COUNTER QUEUE
                                $("#current-queue-box-"+$poli).attr("data-queue",0);
                                //HIDE LOADING SCREEN
                                $("#loading-screen-queue-"+$poli).hide();

                                //REMOVE BOX
                                $("#current-queue-box-"+$poli).children(".small-box").html("");
                                $("#button-confirm-queue-"+$poli).hide();
                            }else{
                                alertify.error(data.msg);
                                $("#loading-screen-queue-"+$poli).hide();
                            }
                        },
                        error: function(xhr, status, error) {
                            //var err = eval("(" + xhr.responseText + ")");
                            //alertify.error(xhr.responseText);
                            //HIDE LOADING SCREEN
                            alertify.error("Cannot response server !");
                            $("#loading-screen-queue-"+$poli).hide();
                        }
                    });
                }
            ).setHeader($title);
        }

        function getNextQueueList(){
            var $clinic = $("#clinic-header-value").val();
            var $base_url = "<?php echo site_url();?>/";
            $.ajax({
                url: $base_url+"reservation/getQueueNext",
                data: {clinic : $clinic},
                type: "POST",
                dataType: 'json',
                cache:false,
                beforeSend:function(){

                },
                success:function(data){
                    if(data.status != "error"){
                        $("#next-queue-box-list").html("");
                        $queue_data = data.output;

                        $.each($queue_data, function( key, value ) {
                            renderNextQueue(value.noQueue,value.poliName,value.patientName,value.poliID);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    //HIDE LOADING SCREEN
                }
            });
        }

        function renderNextQueue(q_number,poli_name, patient_name, poli){
            var $qnumber = $("#current-queue-info").attr("data-queue-number");
            var $poli = $("#current-queue-info").attr("data-queue-poli");
            var $doctor =  $("#current-queue-info").attr("data-queue-doctor");

            var $div = $("<div>", {class: "col-lg-12 col-xs-12"});
            var $small_box = $("<div>", {class: "small-box-list bg-green", "data-value": "0"});
            var $inner = $("<div>", {class: "inner", "data-value": "0"});
            var $queue_number = $("<h3>").html(q_number+" - "+patient_name);
            var $poli_doctor = $("<p>").html(poli_name);

            $queue_number.appendTo($inner);
            $poli_doctor.appendTo($inner);
            $inner.appendTo($small_box);
            $small_box.appendTo($div);
            $("#next-queue-box-list").append($div);
        }
    });
</script>


