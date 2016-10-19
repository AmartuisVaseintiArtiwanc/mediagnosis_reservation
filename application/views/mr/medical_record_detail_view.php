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
    <!--Autocomplete-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/easyAutocomplete-1.3.5/easy-autocomplete.min.css">
    <!--Sweet Alert-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.css">

    <!--Sweet Alert-->
    <script src="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!--Select2-->
    <script src="<?php echo base_url();?>assets/plugins/jQuery/jQuery-2.2.0.min.js"></script>
    <!--Autofit for Textarea-->
    <script src="<?php echo base_url();?>assets/plugins/autosize/autosize.min.js"></script>
    <!--Autocomplete-->
    <script src="<?php echo base_url();?>assets/plugins/easyAutocomplete-1.3.5/jquery.easy-autocomplete.min.js"></script>

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
</style>
<body>
<!--  Start here -->

<body>

<div id="wrap">
    <div id="accordian">
        <div class="step" id="step1">
            <div class="number">
                <span>1</span>
            </div>
            <div class="title">
                <h1>IDENTITAS PASIEN</h1>
            </div>
            <div class="modify">
                <i class="fa fa-plus-circle"></i>
            </div>
        </div>
        <div class="content w3-row" id="email">
            <div class="w3-col m6">
                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4>RIWAYAT PENYAKIT</h4>
                    </div>

                    <div class="w3-container">
                        <ul class="w3-ul w3-large" id="disease-history-ul">
                        </ul>
                    </div>
                </div>

                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4>RIWAYAT PENGOBATAN</h4>
                    </div>

                    <div class="w3-container">
                        <ul class="w3-ul w3-large" id="medication-history-ul">
                        </ul>
                    </div>
                </div>

                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4>RIWAYAT ALERGI</h4>
                    </div>

                    <div class="w3-container">
                        <ul class="w3-ul w3-large" id="alergy-history-ul">
                        </ul>
                    </div>
                </div>
                <br>
            </div>
            <div class="w3-col m6">
                <table class="w3-table-all w3-hoverable" id="identity-table">
                    <tr>
                        <td>No. Kartu BPJS</td>
                        <td><?php echo $header->bpjsID;?></td>
                    </tr>
                    <tr>
                        <td>NO KTP</td>
                        <td><?php echo $header->ktpID;?></td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td><?php echo $header->patientName;?></td>
                    </tr>
                    <tr>
                        <td>Tempat, Tanngal Lahir</td>
                        <td><?php echo $header->dob;?></td>
                    </tr>
                    <tr>
                        <td>Perusahaan</td>
                        <td><?php echo $header->address;?></td>
                    </tr>
                    <tr>
                        <td>Telepon</td>
                        <td><?php echo $header->phoneNumber;?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- HASIL PEMERIKSAAN -->
        <div class="step" id="step2">
            <div class="number">
                <span>2</span>
            </div>
            <div class="title">
                <h1>HASIL PEMERIKSAAN</h1>
            </div>
            <div class="modify">
                <i class="fa fa-plus-circle"></i>
            </div>
        </div>
        <div class="content w3-row">
            <div class="w3-col m6 w3-padding-small">
                <div class="w3-bottombar w3-border-blue w3-margin-right">
                    <h4>SUBJEKTIF</h4>
                </div>

                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4>KELUHAN UTAMA   <!--ERROR MSG--><span class="w3-tag w3-red err-msg" id="main-condition-err-msg"></span></h4>
                    </div>

                    <div class="w3-container w3-margin-top">
                        <p><!--VALUE-->
                            <ul class="w3-ul w3-card-4" id="main-condition-ul">
                                <li class="w3-padding-8">
                                    <?php echo $detail->mainConditionText;?>
                                </li>
                            </ul>
                        </p>
                    </div>
                </div>

                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4>MULAI SEJAK  <span class="w3-tag w3-red err-msg" id="condition-date-err-msg"></span></h4>
                    </div>
                    <div class="w3-container w3-margin-top">
                        <p>
                            <ul class="w3-ul w3-card-4" id="condition-date-ul">
                                <li class="w3-padding-8">
                                    <?php echo $detail->conditionDate;?>
                                </li>
                            </ul>
                            <br/>
                            <!--ERROR MSG-->
                        </p>
                    </div>
                </div>

                <!--ADDITIONAL CONDITION-->
                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4 class="w3-left">KELUHAN TAMBAHAN </h4>
                    </div>
                    <div class="w3-container w3-margin-top">
                        <p>
                            <ul class="w3-ul w3-card-4 w3-ul-list" id="additional-condition-ul">
                                <?php foreach($additional_condition as $row){?>
                                    <li class="w3-padding-8">
                                        <?php echo $row['additionalConditionText'];?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </p>
                    </div>
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
                            <label class="w3-label">Tekenan Darah</label> <span class="w3-tag w3-red" id="blood-preasure-err-msg"></span>
                            <div class="w3-row">
                                <div class="w3-col m6">
                                    <?php echo $physical_examination->bloodPreasureLow."/ ".$physical_examination->bloodPreasureHigh;?>
                                </div>
                                <div class="w3-col m6">
                                    <label class="w3-padding">mmHg</label>
                                </div>
                            </div>
                        </p>
                        <p>
                            <label class="w3-label">Tekanan Pernapasan</label> <span class="w3-tag w3-red" id="respiration-err-msg"></span>
                            <div class="w3-row">
                                <div class="w3-col m6">
                                    <?php echo $physical_examination->respirationRate;?>
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
                                    <?php echo $physical_examination->pulse;?>
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
                                    <?php echo $physical_examination->temperature;?>
                                </div>
                                <div class="w3-col m6">
                                    <label class="w3-padding">Celcius</label>
                                </div>
                            </div>
                        </p>
                        <p>
                            <label class="w3-label">Tinggi Badan</label> <span class="w3-tag w3-red" id="height-err-msg"></span>
                            <div class="w3-row">
                                <div class="w3-col m6">
                                    <?php echo $physical_examination->height;?>
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
                                    <?php echo $physical_examination->weight;?>
                                </div>
                                <div class="w3-col m6">
                                    <label class="w3-padding">Kg</label>
                                </div>
                            </div>
                        </p>
                    </form>
                </div>

                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4 class="w3-left">PEMERIKSAAN PENUNJANG  <span class="w3-tag w3-red err-msg" id="support-examination-err-msg"></span></h4>
                    </div>
                    <form class="w3-container w3-margin-top">
                        <!--ERROR MSG-->
                        <p>
                        <ul class="w3-ul w3-ul-list w3-card-4" id="support-examination-ul">
                            <?php foreach($support_examination as $row){?>
                                <li class="w3-padding-small">
                                    <div class="w3-row">
                                        <div class="w3-col m6 w3-padding-small">
                                            <?php echo $row['supportExaminationColumnName'];?>
                                        </div>
                                        <div class="w3-col m6 w3-padding-small">
                                            : <?php echo $row['supportExaminationValue'];?>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                        </p>
                        <br/>
                    </form>
                </div>
            </div>
        </div>

        <!-- DIAGNOSA / ANALISA -->
        <div class="step" id="step3">
            <div class="number">
                <span>3</span>
            </div>
            <div class="title">
                <h1>DIAGNOSA / ANALISA</h1>
            </div>
            <div class="modify">
                <i class="fa fa-plus-circle"></i>
            </div>
        </div>
        <div class="content w3-row">
            <!--WORKING DIAGNOSE-->
            <div class="w3-col m6 w3-padding-small">
                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4 class="w3-left">DIAGNOSA KERJA  <span class="w3-tag w3-red err-msg" id="working-diagnose-err-msg"></span></h4>
                    </div>

                    <form class="w3-container w3-margin-top">
                        <p>
                            <ul class="w3-ul w3-card-4" id="working-diagnose-ul">
                                <li class="w3-padding-8">
                                    <?php echo $detail->diseaseName;?>
                                </li>
                            </ul>
                        </p>
                        <br/>
                    </form>
                </div>
            </div>
            <!--SUPPORT DIAGNOSE-->
            <div class="w3-col m6 w3-padding-small">
                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4 class="w3-left">DIAGNOSA BANDING  <span class="w3-tag w3-red err-msg" id="support-diagnose-err-msg"></span></h4>
                    </div>
                    <form class="w3-container w3-margin-top">
                        <!--ERROR MSG-->
                        <p>
                            <ul class="w3-ul w3-ul-list w3-card-4" id="support-diagnose-ul">
                                <?php foreach($support_diagnose as $row){?>
                                    <li class="w3-padding-8">
                                        <?php echo $row['diseaseName'];?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </p>
                        <br/>
                    </form>
                </div>
            </div>
        </div>

        <!-- PENATALAKSANAAN -->
        <div class="step" id="step4">
            <div class="number">
                <span>4</span>
            </div>
            <div class="title">
                <h1>PENATALAKSANAAN</h1>
            </div>
            <div class="modify">
                <i class="fa fa-plus-circle"></i>
            </div>
        </div>
        <div class="content w3-row">
            <!--MEDICATION-->
            <div class="w3-col m6 w3-padding-small">
                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4 class="w3-left">TERAPI   <span class="w3-tag w3-red err-msg" id="medication-err-msg"></span></h4>
                    </div>
                    <form class="w3-container w3-margin-top">
                        <!--ERROR MSG-->
                        <p>
                            <ul class="w3-ul w3-ul-list w3-card-4" id="medication-ul">
                                <?php foreach($medication as $row){?>
                                    <li class="w3-padding-8">
                                        <?php echo $row['medicationText'];?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </p>
                        <br/>
                    </form>
                </div>
            </div>

            <div class="w3-col m6 w3-padding-small">
                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4 class="w3-left">RUJUKAN</h4>
                    </div>

                    <form class="w3-container w3-margin-top">
                        <p>
                            <ul class="w3-ul w3-ul-list w3-card-4">
                                <li class="w3-padding-8">
                                    <?php echo $detail->reference;?>
                                </li>
                            </ul>
                        </p>
                        <br/>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("#otp-input-form").hide();
        $(".w3-modal").show();

        $("#request-otp-btn").click(function(){
            $("#otp-input-form").show();
        });
    });
</script>
</body>
</html>
