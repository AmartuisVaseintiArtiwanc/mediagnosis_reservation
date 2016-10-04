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
    <!--Font Awesome-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/font-awesome/css/font-awesome.css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700,600' rel='stylesheet' type='text/css'>
    <!--Sweet Alert-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.css">

    <!--Sweet Alert-->
    <script src="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!--Select2-->
    <script src="<?php echo base_url();?>assets/plugins/jQuery/jQuery-2.2.0.min.js"></script>

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
                    <button class="w3-btn w3-padding-medium w3-margin-left w3-teal">DATE</button>
                    <button class="w3-btn w3-padding-medium w3-margin-left w3-teal">PERIODE</button>
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
</div>

</body>
</html>
