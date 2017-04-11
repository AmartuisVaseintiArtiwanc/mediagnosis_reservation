<?php $this->load->helper('HTML');
?>
<!--Sweet Alert-->
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.css">
<!--Sweet Alert-->
<script src="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<style>
    .cd-error-message{
        font-size:12px;
        visibility: visible;
    }
    .lookup-btn:hover{
        background-color: #3c8dbc;
        color: #fff;
        cursor: pointer;
    }
    .hidden{
        display: none;
    }
    table.dataTable thead > tr > th{
        padding-right: 8px!important;
    }
    th.dt-center, td.dt-center { text-align: center; }
    .alert-header{
        margin: 0px;
    }
    .margin-md{
        margin-top: 5px;
    }
    .right{
        right: 0px;
    }
</style>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Rekam Medis Pasien
        <small>Detil Rekam Medis</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Rekam Medis</a></li>
        <li>Daftar Pasien</li>
        <li>Daftar Rekam Medis</li>
        <li class="active">Detil Rekam Medis</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="box" id="content-container" >
        <div class="box-header">
            <h3 class="box-title">
                <b><?php echo $header->clinicName;?></b> - <b><?php echo $header->poliName;?></b>
                - <b><?php
                        $date=date_create("$header->created");
                        echo date_format($date,"d F Y");?></b>
            </h3>
            <a class="right" href="<?php echo site_url()."/MedicalRecord/medicalRecordListByPatient/".$patient;?>">
                <button type="button" class="btn btn-primary btn-xs">
                    <span class="glyphicon glyphicon-arrow-left"></span>&nbsp Kembali ke Daftar Pasien
                </button>
            </a>
        </div>
        <div class="box-body">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab-patient" data-toggle="tab">Pasien</a></li>
                    <li><a href="#tab-examination" data-toggle="tab">Pemeriksaan</a></li>
                    <li><a href="#tab-diagnose" data-toggle="tab">Diagnosa</a></li>
                    <li><a href="#tab-medication" data-toggle="tab">Penatalaksanaan</a></li>
                </ul>
                <div class="tab-content">
                    <!--Tab Data Patient-->
                    <div class="tab-pane active" id="tab-patient">
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="box-body">
                                    <form>
                                        <div class="form-group">
                                            <label class="control-label">Nama Pasien :</label>
                                            <input type="text" class="form-control" id="patient-name-input"
                                                   value="<?php echo $header->patientName;?>" placeholder="Nama Pasien" disabled/>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">No. KTP :</label>
                                            <input type="text" class="form-control" id="no-ktp-input"
                                                   value="<?php echo $header->ktpID;?>" placeholder="No. KTP [16 Digit]" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">No. BPJS :</label>
                                            <input type="text" class="form-control" id="no-bpjs-input"
                                                   value="<?php echo $header->bpjsID;?>" placeholder="No. BPJS [15 Digit]" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Alamat :</label>
                                            <textarea class="form-control" id="address-input" placeholder="Alamat" disabled><?php echo $header->address;?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Jenis Kelamin :</label>
                                            <input type="text" class="form-control" id="gender-input"
                                                   value="<?php echo $header->gender;?>" placeholder="Jenis Kelamin" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Status Partisipan :</label>
                                            <input type="text" class="form-control" id="participant-status-input"
                                                   value="<?php echo $header->participantStatus;?>" placeholder="Status" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Tipe Partisipan :</label>
                                            <input type="text" class="form-control" id="participant-type-input"
                                                   value="<?php echo $header->participantType;?>" placeholder="Tipe" disabled>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Tab Data Examination-->
                    <div class="tab-pane" id="tab-examination">
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Pemeriksaan Fisik</h3>
                                </div>
                                <div class="box-body">
                                    <form>
                                        <div class="form-group">
                                            <label class="control-label">Kesadaran :</label>
                                            <input type="text" class="form-control" id="conscious-input"
                                                   value="<?php echo $physical_examination->conscious;?>" placeholder="Kesadaran" disabled/>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Tekanan Darah :</label>
                                            <input type="text" class="form-control" id="blood-preasure-input"
                                                   value="<?php echo $physical_examination->bloodPreasureLow."/ ".$physical_examination->bloodPreasureHigh;?> mmHg"
                                                   placeholder="xx/xx mmHg" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Tekanan Pernapasan :</label>
                                            <input type="text" class="form-control" id="respiration-input"
                                                   value="<?php echo $physical_examination->respirationRate;?>/menit" placeholder="xx/menit" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Denyut Nadi :</label>
                                            <input type="text" class="form-control" id="pulse-input"
                                                   value="<?php echo $physical_examination->pulse;?>/menit" placeholder="xx/menit" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Suhu Tubuh :</label>
                                            <input type="text" class="form-control" id="temperature-input"
                                                   value="<?php echo $physical_examination->temperature;?> Celcius" placeholder="x Celcius" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Tinggi Badan :</label>
                                            <input type="text" class="form-control" id="height-input"
                                                   value="<?php echo $physical_examination->height;?> cm" placeholder="xx cm" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Berat Badan :</label>
                                            <input type="text" class="form-control" id="weight-input"
                                                   value=" <?php echo $physical_examination->weight;?> kg" placeholder="xx kg" disabled>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Pemeriksaan Penunjang</h3>
                                </div>
                                <div class="box-body">
                                    <form>
                                        <?php foreach($support_examination as $row){?>
                                            <div class="form-group">
                                                <label class="control-label"><?php echo $row['supportExaminationColumnName'];?></label>
                                                <textarea class="form-control margin-md" disabled><?php echo $row['supportExaminationValue'];?></textarea>
                                            </div>
                                        <?php } ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab-diagnose">
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Keluhan</h3>
                                </div>
                                <div class="box-body">
                                    <form>
                                        <div class="form-group">
                                            <label class="control-label">Keluhan Utama :</label>
                                            <textarea class="form-control" id="address-input" placeholder="Keluhan" disabled><?php echo $detail->mainConditionText;?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Mulai Sejak :</label>
                                            <textarea class="form-control" id="address-input" placeholder="Sejak" disabled><?php echo $detail->conditionDate;?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Keluhan Tambahan :</label>
                                            <?php foreach($additional_condition as $row){?>
                                                <textarea class="form-control margin-md" disabled><?php echo $row['additionalConditionText'];?></textarea>
                                            <?php } ?>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Diagnosa</h3>
                                </div>
                                <div class="box-body">
                                    <form>
                                        <div class="form-group">
                                            <label class="control-label">Diagnosa Kerja :</label>
                                            <textarea class="form-control" id="main-diagnose-input" placeholder="Diagnosa Kerja" disabled><?php echo $detail->diseaseName;?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Diagnosa Banding :</label>
                                            <?php foreach($support_diagnose as $row){?>
                                                <textarea class="form-control margin-md" disabled><?php echo $row['diseaseName'];?></textarea>
                                            <?php } ?>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab-medication">
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Penatalaksanaan</h3>
                                </div>
                                <div class="box-body">
                                    <form>
                                        <div class="form-group">
                                            <label class="control-label">Terapi :</label>
                                            <?php foreach($medication as $row){?>
                                                <textarea class="form-control margin-md" disabled><?php echo $row['medicationText'];?></textarea>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Rujukan :</label>
                                            <textarea class="form-control" id="reference-input" placeholder="Rujukan" disabled><?php echo $detail->reference;?></textarea>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Kunjungan</h3>
                                </div>
                                <div class="box-body">
                                    <form>
                                        <div class="form-group">
                                            <label class="control-label">Jenis Kunjungan :</label>
                                            <input type="text" class="form-control" id="patient-name-input"
                                                   value="<?php echo $detail->visitType;?>" placeholder="Kunjungan" disabled/>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Status Pulang :</label>
                                            <input type="text" class="form-control" id="patient-name-input"
                                                   value="<?php echo $detail->statusDiagnose;?>" placeholder="Status Pulang" disabled/>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(function() {
        var $base_url = "<?php echo site_url();?>/";

    });
</script>