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
                                        <i class="fa fa-remove"></i><b> TIDAK ADA</b></a>
                                </div>
                                <div class="col-lg-6">
                                    <a href="#" class="btn btn-lg btn-success btn-block btn-reservation-confirmation" data-value="confirm">
                                        <i class="fa fa-check-circle"></i><b> ADA</b></a>
                                </div>
                            </div>

                            <input type="hidden" id="detail-reservation-value" value="0" />

                            <div class="overlay loading-screen-queue">
                                <i class="fa fa-user-times"></i>
                                <br/>
                                <h3 class="text-center">TIDAK ADA ANTRIAN</h3>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="box" id="content-container" >
                <div class="box-header">
                    <h3 class="box-title">Antrian Sebelumnya</h3>
                </div>

                <div class="box-body">
                    <?php foreach($reservation_latest_queue as $row) { ?>
                        <div class="col-lg-12 col-xs-12">
                            <div class="small-box-list bg-green">
                                <div class="inner">
                                    <h3><?php echo $row['noQueue'];?></h3>
                                    <p><?php echo strtoupper($row['poliName']);?>- <?php echo ($row['doctorName']);?></p>
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
    </div>
</section>

<script>
    $(function(){
        var count = 1;

        var $detailID,$headerID;
        function getCurrentQueue() {
            var $base_url = "<?php echo site_url();?>/";
            var $currQueue = $("#current-queue-box").attr("data-queue");
            var $clinic = $("#clinic-header-value").val();
            if($currQueue == 0) {
                $.ajax({
                    url: $base_url+"reservation/getQueueCurrent",
                    data: {clinic : $clinic},
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
                            renderQueueBox(data.output['noQueue'],data.output['poliName'],data.output['doctorName']);
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

        function renderQueueBox(q_number,poli_name, doctor_name){
            var $small_box = $("<div>", {class: "small-box bg-green", "data-value": "0"});
            var $inner = $("<div>", {class: "inner", "data-value": "0"});
            var $queue_number = $("<h3>").html(q_number);
            var $poli_doctor = $("<p>").html(poli_name+" - "+doctor_name);

            var $icon = $("<div>", {class: "icon"});
            var $i = $("<i>", {class: "ion ion-person"});
            $i.appendTo($icon)

            $queue_number.appendTo($inner);
            $poli_doctor.appendTo($inner);
            $inner.appendTo($small_box);
            $icon.appendTo($small_box)
            $("#current-queue-box").prepend($small_box);

            $("#button-confirm-queue").removeClass("hide");
            $("#button-confirm-queue").show();
        }

        function alertSound(){
            //new Audio("audio.mp3").play();
        }

        $(".btn-reservation-confirmation").click(function(){
            var $value = $(this).attr("data-value");
            var $title = "Confirmation";
            var $msg = "";
            var detailID = $("#detail-reservation-value").val();
            var $base_url = "<?php echo site_url();?>/";

            if($value=="confirm"){
                $msg="Pasien Ada ?";
                $data = {
                    status : "check",
                    detailID : detailID
                };
            }else if($value=="reject"){
                $msg="Pasien Tidak Ada ?";
                $data = {
                    status : "late",
                    detailID : detailID
                };
            }

            alertify.confirm($msg,
                function(){
                    $.ajax({
                        url: $base_url+"reservation/saveCurrentQueue",
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
                            if(data.status != "error"){
                                //SET COUNTER QUEUE
                                $("#current-queue-box").attr("data-queue",0);
                                //HIDE LOADING SCREEN
                                $(".loading-screen-queue").hide();
                                alertify.success("Cannot response server !");
                                $("#current-queue-box").children(".small-box").html("");
                                $("#button-confirm-queue").hide();
                            }else{
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


