<!DOCTYPE html>
<!-- By Designscrazed.com , just a structure for easy usage. -->

<html lang='en'>
<head>
    <meta charset="UTF-8" />
    <title>
        Sample Page by Designscrazed.com
    </title>
    <!--Main CSS-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/custom/doctor.css">
    <!--Grid W3 System-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/custom/grid.css">
    <!--Bootstrap-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/bootstrap/css/bootstrap.min.css">
    <!--Font Awesome-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/font-awesome/css/font-awesome.css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700,600' rel='stylesheet' type='text/css'>
    <!--Sweet Alert-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.css">
    <!--DatePicker-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/bootstrap-material-datepicker/css/bootstrap-material-datetimepicker.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!--Sweet Alert-->
    <script src="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!--Select2-->
    <script src="<?php echo base_url();?>assets/plugins/jQuery/jQuery-2.2.0.min.js"></script>
    <!--DatePicker-->
    <script src="<?php echo base_url();?>assets/plugins/bootstrap-material-datepicker/js/moment.js"></script>
    <script src="<?php echo base_url();?>assets/plugins/bootstrap-material-datepicker/js/bootstrap-material-datetimepicker.js"></script>

</head>
<style>

    #btn-save-medical-record,#btn-cancel-medical-record{
        margin-top: 32px;
        padding-left: 128px;
        padding-right: 128px;
        padding-top: 24px;
        padding-bottom: 24px;
    }
    .w3-padding-lr{
        padding-left: 12px;
        padding-right: 12px;
    }

    .fa-icon{
        font-size: 70px;
    }
    .search-btn-container button{
        min-width: 150px;
    }
</style>
<body>
<!--  Start here -->

<body>
<div class="headline">

    <h6></h6>
    <h1>
        <b>OTP MEDICAL RECORD</b>
    </h1>
    <h6></h6>
    <h2><?php echo $patient_data->patientName;?></h2>
</div>

<div id="wrap">
    <div id="accordian">
        <div class="content">
            <div class="w3-card-4" style="width:50%;margin: auto">
                <form class="w3-container " action="">
                   <div class="w3-section" id="otp-input-form">
                        <label class="w3-large"><b>Masukan Kode OTP</b></label>
                        <input class="w3-input w3-border w3-margin-bottom" id="otp-input" type="text" placeholder="Kode OTP" name="otp_input">
                   </div>
                    <button class="w3-btn-block w3-teal w3-section w3-padding-xlarge" id="request-otp-btn" type="button">REQUEST OTP</button>
                    <!--
                    <div class="w3-container w3-border-top w3-padding-16">
                        <div class="w3-col m6">
                            <button onclick="document.getElementById('id01').style.display='none'"
                                    type="button" class="w3-btn-block w3-padding-xlarge w3-red">TIDAK ADA</button>
                        </div>
                        <div class="w3-col m6">
                            <button type="button" class="w3-btn-block w3-padding-xlarge w3-green" id="btn-confirm-otp">ADA</button>
                        </div>
                    </div>-->
                    <input type="hidden" id="base-url" value="<?php echo site_url();?>"/>
                    <input type="hidden" id="patient-value" value="<?php echo $patient_data->patientID;?>"/>
                    <input type="hidden" id="detail-reservation-value" value="<?php echo $detail_reservation;?>"/>
                </form>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#request-otp-btn").click(function(){
            var $base_url = $("#base-url").val();
            var $otp = $("#otp-input").val();
            var $patient = $("#patient-value").val();
            var $detail_reservation = $("#detail-reservation-value").val();

            if(validateInput()) {
                var data_post = {
                    patient: $patient,
                    detail_reservation: $detail_reservation,
                    otp: $otp
                };

                $.ajax({
                    url: $base_url + "/MedicalRecord/checkUserOTP",
                    data: data_post,
                    type: "POST",
                    dataType: 'json',
                    beforeSend: function () {
                        $("#load_screen").show();
                    },
                    success: function (data) {
                        if (data.status != 'error') {
                            swal({
                                title: data.msg,
                                text: 'This Page will be redirect after a few second !',
                                type: 'success',
                                allowOutsideClick: true,
                                showConfirmButton: false
                            })
                            window.setTimeout(function () {
                                location.href = $base_url + "/MedicalRecord/getMedicalRecordList/"+$detail_reservation+"/"+$patient;
                            }, 2000);

                        } else {
                            swal(
                                data.msg,
                                '',
                                'error'
                            )
                        }
                    },
                    error: function (xhr, status, error) {
                        //var err = eval("(" + xhr.responseText + ")");
                        $("#load_screen").hide();
                        swal(
                            "Server Error ! Please Try Again",
                            '',
                            'error'
                        )
                    }
                });
            }
        });

        function validateInput(){
            var $otp = $("#otp-input").val();
            var $err = 0;
            var $msg = "";

            if($otp == ""){
                $msg="Kode OTP tidak boleh kosong !";
                $err++;
            }else if($otp.length != 6){
                $msg="Kode OTP harus 6 karakter !";
                $err++;
            }

            if($err != 0){
                swal(
                    $msg,
                    '',
                    'error'
                );
                return false;
            }else{
                return true;
            }
        }

    });
</script>
</body>
</html>
