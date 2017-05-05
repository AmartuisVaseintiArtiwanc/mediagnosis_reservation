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
        <small>Daftar Pasien</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Rekam Medis</a></li>
        <li class="active">Daftar Pasien</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="box" id="content-container" >
        <div class="box-body">
            <table  class="table table-bordered table-striped tbl-master" id="dataTables-list">
                <thead>
                <tr>
                    <th style = "text-align:left;">MRIS</th>
					<th style = "text-align:left;">Nama Klinik</th>
                    <th style = "text-align:left;">Nama</th>
                    <th style = "text-align:left;">Tanggal Lahir</th>
                    <th style = "text-align:left;">Jenis Kelamin</th>
                    <th style = "text-align:left;">Alamat</th>
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
                url: $base_url+"MedicalRecord/getMedicalRecordListDataBySuperUser",
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
                    { "mDataProp": "mrisNumber"},
                    { "mDataProp": "clinicName"},
					{ "mDataProp": "patientName"},
                    { "mDataProp": "dob"},
                    { "mDataProp": "gender"},
                    { "mDataProp": "address"},
                    { "mDataProp": "patientID"}
                ],
                "columnDefs": [
                    {
                        "width": "15%",
                        "targets": 0
                    },
					{
                        "width": "10%",
                        "targets": 1
                    },
                    {
                        "width": "15%",
                        "targets": 2
                    },
                    {
                        "width": "10%",
                        "targets": 3
                    },
                    {
                        "width": "10%",
                        "targets": 4
                    },
                    {
                        "width": "40%",
                        "targets": 5
                    },
                    {
                        "width": "10%",
                        "targets": [ -1 ], //last column
                        "orderable": false,//set not orderable
                        "className": "dt-center",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            var $a_ref = $("<a>", { href:$base_url+"MedicalRecord/medicalRecordListByPatient/"+cellData});
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