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
</style>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Rekam Medis Pasien
        <small>Daftar Rekam Medis</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Rekam Medis</a></li>
        <li>Daftar Pasien</li>
        <li class="active">Daftar Rekam Medis</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="box" id="content-container" >
        <div class="box-header">
            <h3 class="box-title">Pasien : <b><?php echo $patient_data->patientName;?></b></h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-lg-8">
                    <a href="<?php echo site_url()."/MedicalRecord/medicalRecordListBySuperUser";?>">
                        <button type="button" class="btn btn-primary btn-xl">
                            <span class="glyphicon glyphicon-arrow-left"></span>&nbsp Kembali ke Daftar Pasien
                        </button>
                    </a>
                </div>
            </div>
            <table  class="table table-bordered table-striped tbl-master" id="dataTables-list">
                <thead>
                <tr>
                    <th style = "text-align:left;">Tanggal MRIS</th>
                    <th style = "text-align:left;">Tipe Kunjungan</th>
                    <th style = "text-align:left;">Klinik</th>
                    <th style = "text-align:left;">Dokter</th>
                    <th style = "text-align:center;">Option</th>
                </tr>
                </thead>

                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
    $(function() {
        var $base_url = "<?php echo site_url();?>/";

        getPatientData();
        function getPatientData(){
            $.ajax({
                url: $base_url+"MedicalRecord/getMedicalRecordListDataByPatient/"+<?php echo $patient_data->patientID;?>,
                type: "POST",
                dataType: 'json',
                cache:false,
                success:function(data){
                    if(data.status != "error"){
                        renderPatientData(data);
                        $(".overlay").hide();
                    }else{
                        alertify.alert(data.msg).setHeader("<h3 class='alert-header text-red'>Error</h3>");
                        $(".overlay").show();
                    }
                },
                error: function(xhr, status, error) {
                    //var err = eval("(" + xhr.responseText + ")");
                    //alertify.error(xhr.responseText);
                    alertify.error("Terjadi kesalahan server!");
                }
            });
        }

        function renderPatientData($data){
            $('#dataTables-list').DataTable( {
                "aaData": $data.data,
                "lengthChange": false,
                "autoWidth": false,
                "aoColumns": [
                    { "mDataProp": "created"},
                    { "mDataProp": "reservationType"},
                    { "mDataProp": "clinicName"},
                    { "mDataProp": "doctorName"},
                    { "mDataProp": "medicalRecordID"}
                ],
                "columnDefs": [
                    {
                        "width": "20%",
                        "targets": 0
                    },
                    {
                        "width": "20%",
                        "targets": 1
                    },
                    {
                        "width": "25%",
                        "targets": 2
                    },
                    {
                        "width": "25%",
                        "targets": 3
                    },
                    {
                        "width": "10%",
                        "targets": [ -1 ], //last column
                        "orderable": false,//set not orderable
                        "className": "dt-center",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            var $a_ref = $("<a>", { href:$base_url+"MedicalRecord/medialRecordDetailByPatient/"+cellData+"/"+rowData.patientID});
                            var $btn_detail = $("<button>", { class:"btn btn-primary btn-xs","type": "button",
                                "data-value": cellData});
                            $btn_detail.append("<span class='glyphicon glyphicon-search'></span>&nbsp lihat MRIS");
                            $a_ref.append($btn_detail);
                            $(td).html($a_ref);
                        }
                    },
                ]
            } );
        }
    });
</script>