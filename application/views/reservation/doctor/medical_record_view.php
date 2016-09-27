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
            </div>
            <div class="w3-col m6">
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
                        <td>Tempat, Tanngal Lahir</td>
                        <td><?php echo $patient_data->dob;?></td>
                    </tr>
                    <tr>
                        <td>Perusahaan</td>
                        <td><?php echo $patient_data->address;?></td>
                    </tr>
                    <tr>
                        <td>Telepon</td>
                        <td><?php echo $patient_data->phoneNumber;?></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>satriaws@gmail.com</td>
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
                        <h4>KELUHAN UTAMA</h4>
                    </div>

                    <div class="w3-container">
                        <p>
                            <textarea class="w3-input" id="main-condition-text"
                                data-ul="#main-condition-ul" data-li="#main-condition-value"
                                data-label="#main-condition-err-msg"></textarea>
                            <!--ERROR MSG-->
                            <span class="w3-tag w3-red err-msg" id="main-condition-err-msg"></span>
                            <!--VALUE-->
                            <ul class="w3-ul w3-card-4 w3-hide" id="main-condition-ul">
                                <li class="w3-padding-8">
                                    <span class="w3-large" id="main-condition-value" data-value="" data-status=""></span>
                                      <span
                                          data-input-value="#main-condition-value"
                                            data-input-element="#main-condition-text"
                                            data-ul="#main-condition-ul"
                                            class="w3-closebtn w3-margin-right w3-medium"><i class="fa fa-pencil"></i></span>
                                </li>
                            </ul>
                        </p>
                    </div>
                </div>

                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4>MULAI SEJAK</h4>
                    </div>
                    <div class="w3-container">
                        <p>
                            <textarea class="w3-input" id="condition-date-text" data-label="#condition-date-err-msg"></textarea>
                            <br/>
                            <!--ERROR MSG-->
                            <span class="w3-tag w3-red err-msg" id="condition-date-err-msg"></span>
                        </p>
                    </div>
                </div>

                <!--ADDITIONAL CONDITION-->
                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4 class="w3-left">KELUHAN TAMBAHAN</h4>
                    </div>
                    <div class="w3-container w3-margin-top">
                        <!--ERROR MSG-->
                        <span class="w3-tag w3-red err-msg" id="add-condition-err-msg"></span>
                        <p>
                            <ul class="w3-ul w3-card-4 w3-ul-list" id="additional-condition-ul">
                                <li class="w3-padding-8">
                                    <span class="w3-closebtn w3-closebtn-list w3-large w3-margin-right">x</span><br/>
                                    <div class="w3-medium w3-padding-medium">
                                        <textarea class="w3-input add-codition-li-text" data-label="#add-condition-err-msg"></textarea>
                                    </div>
                                </li>
                            </ul>
                        </p>
                        <button class="w3-btn w3-round-xxlarge w3-ripple w3-left w3-red w3-margin"
                                id="btn-add-additional-condition">+ TAMBAH BARU</button>
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
                                <div class="w3-col m4">
                                    <input class="w3-input" id="blood-preasure-low-input" data-label="#blood-preasure-err-msg" type="text">
                                </div>
                                <div class="w3-col m4 w3-padding-lr">
                                    <input class="w3-input" id="blood-preasure-high-input" data-label="#blood-preasure-err-msg" type="text">
                                </div>
                                <div class="w3-col m4">
                                    <label class="w3-padding">mmHg</label>
                                </div>
                            </div>
                        </p>
                        <p>
                            <label class="w3-label">Tekanan Pernapasan</label> <span class="w3-tag w3-red" id="respiration-err-msg"></span>
                            <div class="w3-row">
                                <div class="w3-col m6">
                                    <input class="w3-input" id="respiration-input" type="text" data-label="#respiration-err-msg">
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
                                    <input class="w3-input" id="pulse-input" data-label="#pulse-err-msg" type="text">
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
                                    <input class="w3-input" id="temperature-input" data-label="#temperature-err-msg" type="text">
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
                                    <input class="w3-input" id="height-input" data-label="#height-err-msg" type="text">
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
                                    <input class="w3-input" id="weight-input" data-label="#weight-err-msg" type="text">
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
                        <h4 class="w3-left">PEMERIKSAAN PENUNJANG</h4>
                    </div>
                    <form class="w3-container w3-margin-top">
                        <!--ERROR MSG-->
                        <span class="w3-tag w3-red err-msg" id="support-examination-err-msg"></span>
                        <p>
                            <ul class="w3-ul w3-ul-list w3-card-4" id="support-examination-ul">
                                <li class="w3-padding-small">
                                    <span class="w3-closebtn w3-closebtn-list w3-large w3-margin-right">x</span><br/>
                                    <span class="w3-clear"></span>
                                    <div class="w3-row">
                                        <div class="w3-col m6 w3-padding-small">
                                            <textarea class="w3-input w3-border support-examination-column" data-label="#support-examination-err-msg"></textarea>
                                        </div>
                                        <div class="w3-col m6 w3-padding-small">
                                            <textarea class="w3-input w3-border support-examination-value" data-label="#support-examination-err-msg"></textarea>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </p>
                        <button class="w3-btn w3-round-xxlarge w3-ripple w3-left w3-red w3-margin"
                                id="btn-add-support-examination">+ TAMBAH BARU</button>
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
                        <h4 class="w3-left">DIAGNOSA KERJA</h4>
                    </div>

                    <form class="w3-container">
                        <p>
                            <textarea class="w3-input" id="working-diagnose-text"
                                data-label="#working-diagnose-err-msg"
                                data-ul="#working-diagnose-ul" data-li="#working-diagnose-value"></textarea>
                            <br>
                            <!--ERROR MSG-->
                            <span class="w3-tag w3-red err-msg" id="working-diagnose-err-msg"></span>
                            <!--VALUE-->
                            <ul class="w3-ul w3-card-4 w3-hide" id="working-diagnose-ul">
                                <li class="w3-padding-8">
                                    <span class="w3-large" id="working-diagnose-value" data-value="" data-status=""></span>
                                          <span
                                              data-input-value="#working-diagnose-value"
                                              data-input-element="#working-diagnose-text"
                                              data-ul="#working-diagnose-ul"
                                              class="w3-closebtn w3-margin-right w3-medium">x</span>
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
                        <h4 class="w3-left">DIAGNOSA PENUNJANG</h4>
                    </div>
                    <form class="w3-container w3-margin-top">
                        <!--ERROR MSG-->
                        <span class="w3-tag w3-red err-msg" id="support-diagnose-err-msg"></span>
                        <p>
                            <ul class="w3-ul w3-ul-list w3-card-4" id="support-diagnose-ul">
                                <li class="w3-padding-8">
                                        <span class="w3-closebtn w3-large w3-margin-right w3-closebtn-list">x</span><br>
                                    <div class="w3-medium w3-padding-medium">
                                        <textarea class="w3-input support-diagnose-li-text" data-label="#support-diagnose-err-msg"></textarea>
                                    </div>
                                </li>
                            </ul>
                        </p>
                        <button class="w3-btn w3-round-xxlarge w3-ripple w3-left w3-red w3-margin"
                                id="btn-add-support-diagnose">+ TAMBAH BARU</button>
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
                        <h4 class="w3-left">TERAPI</h4>
                    </div>
                    <form class="w3-container w3-margin-top">
                        <!--ERROR MSG-->
                        <span class="w3-tag w3-red err-msg" id="medication-err-msg"></span>
                        <p>
                            <ul class="w3-ul w3-ul-list w3-card-4" id="medication-ul">
                                <li class="w3-padding-8">
                                        <span class="w3-closebtn w3-closebtn-list w3-large w3-margin-right">x</span><br>
                                    <div class="w3-medium w3-padding-medium">
                                        <textarea class="w3-input medication-li-text" data-label="#medication-err-msg"></textarea>
                                    </div>
                                </li>
                            </ul>
                        </p>
                       <button class="w3-btn w3-round-xxlarge w3-ripple w3-left w3-red w3-margin" id="btn-add-medication">+ TAMBAH BARU</button>
                        <br/>
                    </form>
                </div>
            </div>

            <div class="w3-col m6 w3-padding-small">
                <div class="w3-card-4 w3-margin">
                    <div class="w3-container w3-green">
                        <h4 class="w3-left">RUJUKAN</h4>
                    </div>

                    <form class="w3-container">
                        <p>
                            <textarea class="w3-input" id="rujukan-text"></textarea>
                        </p>
                        <br/>
                    </form>
                </div>
            </div>
            <input type="hidden" id="base-url" value="<?php echo site_url();?>"/>
        </div>
        <div class="w3-center">
            <button class="w3-btn w3-red" id="btn-cancel-medical-record">BATAL</button>
            <button class="w3-btn w3-green" id="btn-save-medical-record">SIMPAN</button>
        </div>
    </div>
</div>

<script src="<?php echo base_url();?>assets/custom/validate_master.js"></script>
<script src="<?php echo base_url();?>assets/custom/medical_record.js"></script>
</body>
</html>
