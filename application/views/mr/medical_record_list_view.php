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
<div id="wrap">
    <div id="accordian">
        <div class="w3-row content">
            <div class="we-col-m12">
                <div class="w3-btn-group w3-right search-btn-container">
                    <button onclick="document.getElementById('id01').style.display='block'" class="w3-btn w3-padding-medium w3-margin-left w3-teal">DATE</button>
                    <button onclick="document.getElementById('id01').style.display='block'" class="w3-btn w3-padding-medium w3-margin-left w3-teal">PERIODE</button>
                </div>
                <div class="w3-clear"></div>
            </div>
            <br>
            <div class="w3-col m12">
                <ul class="w3-ul w3-card-4">
                    <?php foreach($medical_record_data as $row){?>
                        <li class="w3-padding-16 w3-hover-blue-grey">
                            <div class="w3-row">
                                <div class="w3-col m6">
                                    <div class="w3-padding-medium w3-left w3-circle ">
                                        <i class="fa fa-file-text fa-icon"></i>
                                    </div>
                                    <span class="w3-xlarge">
                                        <?php
                                            $date_created=date_create($row['created']);
                                            echo date_format($date_created,"d F Y");
                                        ?>
                                    </span><br>
                                    <span class="w3-large"><?php echo $row['clinicName']." - ".$row['poliName'];?></span><br>
                                    <span class="w3-large"><?php echo $row['doctorName'];?></span>
                                </div>
                                <div class="w3-col m6">
                                    <div class="w3-padding-medium w3-left w3-circle ">
                                        <i class="fa fa-stethoscope fa-icon"></i>
                                    </div>
                                    <span class="w3-xlarge">DIAGNOSA</span><br>
                                    <span class="w3-large"><?php echo $row['diseaseName'];?></span><br>
                                    <div class="w3-padding-medium">
                                        <a href="<?php echo site_url("MedicalRecord/getMedicalRecordDetail/".$row['medicalRecordID']);?>"><button class="w3-btn w3-light-grey"> <i class="fa fa-search"></i> Lihat Detail</button></a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <!--MODAL-->
    <div id="id01" class="w3-modal">
        <div class="w3-modal-content w3-card-8 w3-animate-zoom" style="max-width:600px">

            <div class="w3-center"><br>
                <span onclick="document.getElementById('id01').style.display='none'" class="w3-closebtn w3-hover-red w3-container w3-padding-8 w3-display-topright" title="Close Modal">&times;</span>
            </div>

            <form class="w3-container w3-margin-top" action="form.asp">
                <div class="w3-section">
                    <label><b>Pilih Tanggal</b></label>
                    <input class="w3-input w3-border w3-margin-bottom" id="date" type="text" placeholder="Enter Username" name="usrname" required>

                    <label><b>Periode</b></label>
                    <input class="w3-input w3-border" id="date-start" type="text" placeholder="Tanggal Mulai" name="psw" required>
                    <input class="w3-input w3-border" id="date-end" type="text" placeholder="Sampai Tanggal" name="psw" required>
                    <button class="w3-btn-block w3-green w3-section w3-padding" type="submit">CARI</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#date').bootstrapMaterialDatePicker
        ({
            time: false,
            clearButton: true
        });

        $('#date-end').bootstrapMaterialDatePicker
        ({
            weekStart: 0, format: 'DD/MM/YYYY', time: false
        });
        $('#date-start').bootstrapMaterialDatePicker
        ({
            weekStart: 0, format: 'DD/MM/YYYY', time: false
        }).on('change', function(e, date)
        {
            $('#date-end').bootstrapMaterialDatePicker('setMinDate', date);
        });
    });
</script>
</body>
</html>
