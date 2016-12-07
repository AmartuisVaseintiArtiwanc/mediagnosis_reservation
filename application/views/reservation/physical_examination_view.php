<!DOCTYPE html>
<!-- By Designscrazed.com , just a structure for easy usage. -->

<html lang='en'>
<head>
    <meta charset="UTF-8" />
    <title>
        Mediagnosis | MRIS
    </title>
    <!--Main CSS-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/custom/doctor.css">
    <!--Grid W3 System-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/custom/grid.css">
    <!--Font Awesome-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/font-awesome/css/font-awesome.css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700,600' rel='stylesheet' type='text/css'>
    <!--Sweet Alert-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.css">

    <!--Sweet Alert-->
    <script src="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!--Select2-->
    <script src="<?php echo base_url();?>assets/plugins/jQuery/jQuery-2.2.0.min.js"></script>
    <!--Autofit for Textarea-->
    <script src="<?php echo base_url();?>assets/plugins/autosize/autosize.min.js"></script>

</head>
<style>
    #identity-table{
        width: 100%;
        position: relative;
    }
    #identity-table tr td{
        padding: 10px;
        margin-top: 10px;
        font-size: 16px;
    }
    #identity-table tr td:first-child{
        width: 35%;
        min-width: 250px;
    }
    .btn-confirmation{
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
    .margin-wrap{
        width: 90%;
        margin: 20px auto;
    }
</style>
<body>
<div class="headline">

    <h6></h6>

    <h1>
        <b><?php echo $header_data->clinicName;?></b> - <b><?php echo $header_data->poliName;?></b>
        <h6></h6>
    </h1>
    <h4> <?php echo $header_data->clinicAddress;?></h4>
    <h1>
        <b>REKAM MEDIS</b>
    </h1>

</div>

<div class="w3-container w3-row margin-wrap">
    <div class="w3-col m6">
        <span class="w3-large w3-text-green">Diperiksa oleh : <?php echo $doctor_data->doctorName;?></span>
    </div>
    <div class="w3-col m6 w3-right-align">
        <span class="w3-large w3-text-green" id="date-name"></span>
        <span class="w3-large w3-text-green">, pkl </span>
        <span class="w3-large w3-text-green" id="time-name"></span>
    </div>
</div>
<div id="wrap">
    <div id="accordian">
        <div class="step" id="step1">
            <div class="number">
                <span>1</span>
            </div>
            <div class="title">
                <h1>PEMERIKSAAN FISIK</h1>
            </div>
            <div class="modify">
                <i class="fa fa-plus-circle"></i>
            </div>
        </div>
        <div class="content w3-row">
            <div class="w3-col m6 w3-padding-small">
                <div class="w3-bottombar w3-border-blue">
                    <h4>DATA PASIEN</h4>
                </div>

                <div class="w3-card-4 w3-margin">
                <table class="w3-table-all w3-hoverable" id="identity-table">
                    <tr>
                        <td>No. Kartu BPJS</td>
                        <td><?php echo $patient_data->bpjsID;?></td>
                    </tr>
                    <tr>
                        <td>NO KTP</td>
                        <td><?php echo $patient_data->ktpID;?></td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td><?php echo $patient_data->patientName;?></td>
                    </tr>
                    <tr>
                        <td>Tempat, Tanggal Lahir</td>
                        <td>
                            <?php
                            $date_created=date_create($patient_data->dob);
                            echo date_format($date_created,"d F Y");?>
                        </td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td><?php echo $patient_data->gender;?></td>
                    </tr>
                    <tr>
                        <td>Status Peserta</td>
                        <td><?php echo $patient_data->participantStatus;?></td>
                    </tr>
                    <tr>
                        <td>Jenis Peserta</td>
                        <td><?php echo $patient_data->participantType;?></td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td><?php echo $patient_data->address;?></td>
                    </tr>
                    <tr>
                        <td>Telepon</td>
                        <td><?php echo $patient_data->phoneNumber;?></td>
                    </tr>
                </table>
                    </div>
            </div>

            <div class="w3-col m6 w3-padding-small">
                <div class="w3-bottombar w3-border-blue w3-margin-right">
                    <h4>OBJEKTIF</h4>
                </div>

                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4>PEMERIKSAAN FISIK</h4>
                    </div>

                    <form class="w3-container">
                        <p>
                            <label class="w3-label">Kesadaran</label> <span class="w3-tag w3-red" id="conscious-err-msg"></span>
                        <div class="w3-row">
                            <select class="w3-select" name="option" id="conscious-input">
                                <option value="Compos Mentis" selected>Compos Mentis</option>
                                <option value="Apatis">Apatis</option>
                                <option value="Delirium">Delirium</option>
                                <option value="Somnolen">Somnolen</option>
                                <option value="Stupor">Stupor</option>
                                <option value="Semi Coma">Semi Coma</option>
                                <option value="Coma">Coma</option>
                            </select>
                        </div>
                        </p>
                        <p>
                            <label class="w3-label">Tekanan Darah</label> <span class="w3-tag w3-red" id="blood-preasure-err-msg"></span>
                        <div class="w3-row">
                            <div class="w3-col m2">
                                <input class="w3-input input-number" id="blood-preasure-low-input" data-label="#blood-preasure-err-msg" type="text">
                            </div>
                            <div class="w3-col m1 w3-center"><span class="w3-xlarge">/</span></div>
                            <div class="w3-col m2">
                                <input class="w3-input input-number" id="blood-preasure-high-input" data-label="#blood-preasure-err-msg" type="text">
                            </div>
                            <div class="w3-col m3">
                                <label class="w3-padding">mmHg</label>
                            </div>
                        </div>
                        </p>
                        <p>
                            <label class="w3-label">Tekanan Pernapasan</label> <span class="w3-tag w3-red" id="respiration-err-msg"></span>
                        <div class="w3-row">
                            <div class="w3-col m6">
                                <input class="w3-input input-number" id="respiration-input" type="text" data-label="#respiration-err-msg">
                            </div>
                            <div class="w3-col m6">
                                <label class="w3-padding">x/minutes</label>
                            </div>
                        </div>
                        </p>
                        <p>
                            <label class="w3-label">Denyut Nadi</label> <span class="w3-tag w3-red" id="pulse-err-msg"></span>
                        <div class="w3-row">
                            <div class="w3-col m6">
                                <input class="w3-input input-number" id="pulse-input" data-label="#pulse-err-msg" type="text">
                            </div>
                            <div class="w3-col m6">
                                <label class="w3-padding">x/minutes</label>
                            </div>
                        </div>
                        </p>
                        <p>
                            <label class="w3-label">Suhu Tubuh</label> <span class="w3-tag w3-red" id="temperature-err-msg"></span>
                        <div class="w3-row">
                            <div class="w3-col m6">
                                <input class="w3-input input-number" id="temperature-input" data-label="#temperature-err-msg" type="text">
                            </div>
                            <div class="w3-col m6">
                                <label class="w3-padding">&deg Celcius</label>
                            </div>
                        </div>
                        </p>
                        <p>
                            <label class="w3-label">Tinggi Badan</label> <span class="w3-tag w3-red" id="height-err-msg"></span>
                        <div class="w3-row">
                            <div class="w3-col m6">
                                <input class="w3-input input-number" id="height-input" data-label="#height-err-msg" type="text">
                            </div>
                            <div class="w3-col m6">
                                <label class="w3-padding">cm</label>
                            </div>
                        </div>
                        </p>
                        <p>
                            <label class="w3-label">Berat Badan</label> <span class="w3-tag w3-red" id="weight-err-msg"></span>
                        <div class="w3-row">
                            <div class="w3-col m6">
                                <input class="w3-input input-number" id="weight-input" data-label="#weight-err-msg" type="text">
                            </div>
                            <div class="w3-col m6">
                                <label class="w3-padding">Kg</label>
                            </div>
                        </div>
                        </p>
                    </form>
                </div>
            </div>
        </div>

        <input type="hidden" id="base-url" value="<?php echo site_url();?>"/>
        <input type="hidden" id="detail-reservation" value="<?php echo $detailReservation;?>"/>
        <input type="hidden" id="patient-id" value="<?php echo $patient_data->patientID;?>"/>

        <div class="w3-center">
            <button class="w3-btn w3-red btn-confirmation" id="btn-cancel-examine" data-value="reject">BATAL</button>
            <button class="w3-btn w3-green btn-confirmation" id="btn-save-examine" data-value="confirm">MULAI PERIKSA</button>
        </div>
    </div>
</div>

<script src="<?php echo base_url();?>assets/custom/validate_master.js"></script>
<script>
    $(function(){
        $base_url = "<?php echo site_url();?>";

        $("#btn-save-examine").click(function(e){
            if(validateInput()){
                saveData();
            }
        });

        $("#btn-cancel-examine").click(function(e){
            var $detailID = $("#detail-reservation").val();
            $data = {
                status : "late",
                detailID : $detailID
            };

            swal({
                title: 'Apakah Anda yakin untuk Membatalkan pemeriksaan ini?',
                text: "Data yang di simpan tidak bisa diganti lagi",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Batalkan!'
            }).then(function() {
                 $.ajax({
                     url: $base_url+"/Reservation/saveCurrentQueue",
                     data: $data    ,
                     type: "POST",
                     dataType: 'json',
                     beforeSend:function(){
                         $("#load_screen").show();
                     },
                     success:function(data){
                         if(data.status != 'error'){
                             swal({
                                 title: data.msg,
                                 text: 'Halaman ini akan di arahkan ke halaman selanjutnya...',
                                 type: 'success',
                                 allowOutsideClick: false,
                                 showConfirmButton:false
                             });
                         // Close This Tab
                         window.setTimeout(function () {
                             window.onbeforeunload = null;
                             window.close();
                         }, 2000);

                         }else{
                             swal(
                                 data.msg,
                                 '',
                                 'error'
                             );
                         }
                     },
                     error: function(xhr, status, error) {
                         //var err = eval("(" + xhr.responseText + ")");
                         $("#load_screen").hide();
                         swal(
                             "Server Error ! Silahkan coba beberapa saat lagi..",
                             '',
                             'error'
                         );
                     }
                });
            });
        });

        function saveData(){
            //Physical Examination
            var $conscious = $("#conscious-input").val();
            var $blood_low = $("#blood-preasure-low-input").val();
            var $blood_high = $("#blood-preasure-high-input").val();
            var $pulse = $("#pulse-input").val();
            var $temperature = $("#temperature-input").val();
            var $respiration = $("#respiration-input").val();
            var $height = $("#height-input").val();
            var $weight = $("#weight-input").val();

            var physcal_data = new Object();
            physcal_data.conscious = $conscious;
            physcal_data.blood_low = $blood_low;
            physcal_data.blood_high = $blood_high;
            physcal_data.pulse = $pulse;
            physcal_data.respiration = $respiration;
            physcal_data.temperature = $temperature;
            physcal_data.height = $height;
            physcal_data.weight = $weight;

            var $detail_reservation = $("#detail-reservation").val();
            var data_post = {
                data :physcal_data,
                detail_reservation : $detail_reservation
            };

            swal({
                title: 'Apakah Anda yakin untuk Menyimpan data ini?',
                text: "Data yang di simpan tidak bisa diganti lagi",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan Data!'
            }).then(function() {
                $.ajax({
                    url: $base_url + "/Reservation/savePhysicalExamination",
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
                                text: 'Halaman ini akan di arahkan ke halaman selanjutnya...',
                                type: 'success',
                                allowOutsideClick: false,
                                showConfirmButton: false
                            })
                            // Close This Tab
                            window.setTimeout(function () {
                                window.onbeforeunload = null;
                                window.close();
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
                            "Server Error ! Silahkan coba beberapa saat lagi..",
                            '',
                            'error'
                        )
                    }

                });

            });
        }

        function validateInput(){
            var err=0;

            // PHYSICAL EXAMINATION
            if(!$("#blood-preasure-low-input").validateRequired({errMsg:"Harap diisi"}) ||
                !$("#blood-preasure-high-input").validateRequired({errMsg:"Harap diisi"}) ){
                err++;
            }
            if(!$("#pulse-input").validateRequired({errMsg:"Harap diisi"})){
                err++;
            }
            if(!$("#temperature-input").validateRequired({errMsg:"Harap diisi"})){
                err++;
            }
            if(!$("#respiration-input").validateRequired({errMsg:"Harap diisi"})){
                err++;
            }
            if(!$("#height-input").validateRequired({errMsg:"Harap diisi"})){
                err++;
            }
            if(!$("#weight-input").validateRequired({errMsg:"Harap diisi"})){
                err++;
            }

            if (err != 0) {
                swal(
                    "Terdapat data yang masih kosong, Silahkan periksa kembali !",
                    '',
                    'error'
                )
                return false;
            } else {
                return true;
            }
        }

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

        $(".input-number").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                    // Allow: Ctrl+A, Command+A
                (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                    // Allow: home, end, left, right, down, up
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });

        window.onbeforeunload = function (event) {
            var message = 'Apakah Anda yakin untuk \'Keluar \' dari halaman ini ?.';
            if (typeof event == 'undefined') {
                event = window.event;
            }
            if (event) {
                event.returnValue = message;
            }
            return message;
        };
    });

</script>
</body>
</html>
