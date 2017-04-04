<?php $this->load->helper('HTML');
?>
<!-- daterange picker -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/daterangepicker/daterangepicker-bs3.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/datepicker/datepicker3.css">
<style>
    .box-hide{
        display: none;
    }
    .btn-block-filter{
        min-width: 120px;
    }
    th.dt-center, td.dt-center { text-align: center; }
    td.dt-right{  text-align: right;  }
    #tbody-report-table{
        display: none;
    }
    .text-green{
        color:#008d4c;
    }
    .text-red{
        color:#d33724;
    }
</style>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Detil Laporan Kunjungan per Poli
        <span class="date-filter"></span>
    </h1>
    <ol class="breadcrumb">
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <p>
        <div class="row">
            <div class="col-lg-8">
                <a href="<?php echo site_url()."/Report/reportClinicPoliVisit/".$super_admin_id."?from=".$start_date."&to=".$end_date;?>">
                    <button type="button" class="btn btn-primary btn-xl">
                        <span class="glyphicon glyphicon-arrow-left"></span>&nbsp Kembali
                    </button>
                </a>

                <button type="button" class="btn btn-primary btn-xl" id="filter-btn">
                    <span class="glyphicon glyphicon-plus"></span>&nbsp Filter
                </button>
            </div>
        </div>
    </p>

    <!-- Filter box -->
    <div class="box box-primary box-hide" id="filter-box">
        <div class="box-header with-border">
            <h3 class="box-title">Filter Data</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <form role="form" id="filter-form">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="patient-name-filter">Nama Pasien</label>
                        <input type="text" class="form-control" id="patient-name-filter" placeholder="Nama Pasien">
                    </div>
                    <div class="form-group">
                        <label for="patient-gender-filter">Jenis Kelamin</label>
                        <input type="text" class="form-control" id="patient-gender-filter" placeholder="Jenis Kelamin">
                    </div>
                    <div class="form-group">

                        <div class="row">
                            <div class="col-xs-6">
                                <label for="patient-age-filter">Umur Minimal</label>
                                <input type="text" class="form-control" id="patient-min-age-filter" placeholder="Umur Minimal">
                            </div>
                            <div class="col-xs-6">
                                <label for="patient-age-filter">Umur Maksimal</label>
                                <input type="text" class="form-control" id="patient-max-age-filter" placeholder="Umur Maksimal">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="reserve-type-filter">Status Sembuh</label>
                        <input type="text" class="form-control" id="status-diagnose-filter" placeholder="Status Sembuh">
                    </div>
                    <div class="form-group">
                        <label for="reserve-type-filter">Jenis Kunjungan</label>
                        <input type="text" class="form-control" id="visit-type-filter" placeholder="Kunjungan">
                    </div>
                    <div class="form-group">
                        <label for="reserve-type-filter">Jenis Pasien</label>
                        <input type="text" class="form-control" id="reservation-type-filter" placeholder="Pasien">
                    </div>
                </div>
                </form>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="button" id="btn-filter-search" class="btn btn-block-filter btn-primary">Cari</button>
            <button type="reset" id="btn-filter-clear" class="btn btn-block-filter btn-default">Clear</button>
        </div>
    </div>

    <div class="box" id="content-container" >
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo $clinic_name;?> - <?php echo $poli_name;?></h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-striped tbl-master" id="report-table">
                <thead>
                <tr>
                    <th style = "text-align:left;">Nama</th>
                    <th style = "text-align:left;">Jenis Kelamin</th>
                    <th style = "text-align:left;">Umur</th>
                    <th style = "text-align:left;">Status Sembuh</th>
                    <th style = "text-align:left;">Jenis Kunjungan</th>
                    <th style = "text-align:left;">Jenis Pasien</th>
                    <th style = "text-align:left;">Tanggal Kunjungan</th>
                </tr>
                </thead>

                <tbody id="tbody-report-table">
                <?php foreach($report_detail_data as $row){?>
                    <tr>
                        <td><?php echo $row['patientName'];?></td>
                        <td><?php echo $row['gender'];?></td>
                        <td><?php echo $row['age'];?></td>
                        <td><?php echo $row['statusDiagnose'];?></td>
                        <td><?php echo $row['visitType'];?></td>
                        <td><?php echo $row['reservationType'];?></td>
                        <td><?php echo $row['reserveDate'];?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</section>

<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- daterangepicker -->
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/daterangepicker/daterangepicker.js"></script>
<script>
    $(function() {
		
		$(".sidebar-menu").find(".active").removeClass("active");
		$(".mediagnosis-navigation-report").addClass("active");

        setFilterDate();
        function setFilterDate(){
            var $start_date = "<?php echo $start_date;?>";
            var $end_date = "<?php echo $end_date;?>";
            $start_date = moment($start_date).format('D MMMM YYYY');
            $end_date = moment($end_date).format('D MMMM YYYY');

            if($start_date == $end_date){
                $(".date-filter").html("("+$start_date+")");
            }else{
                $(".date-filter").html("("+$start_date+" - "+$end_date+")");
            }
            //$("#filter-box").hide();
        }

        var $dataTable = $('#report-table').DataTable({
                "lengthChange": false,
                "autoWidth": false,
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
                        "width": "10%",
                        "targets": 2,
                        "createdCell": function (td, cellData, rowData, row, col) {
                            $(td).html(cellData+" tahun ");
                        }
                    },
                    {
                        "width": "15%",
                        "targets": 3
                    },
                    {
                        "width": "15%",
                        "targets": 4,
                        "createdCell": function (td, cellData, rowData, row, col) {
                            if(cellData == "Kunjungan Sakit"){
                                $(td).addClass("text-red");
                            }else if(cellData == "Kunjungan Sehat"){
                                $(td).addClass("text-green");
                            }
                        }
                    },
                    {
                        "width": "10%",
                        "targets": 5
                    },
                    {
                        "width": "15%",
                        "targets": 6, //last column
                        "createdCell": function (td, cellData, rowData, row, col) {
                            var $reserveDate = moment(cellData).format("D MMM YYYY");
                            $(td).html($reserveDate);
                            $(td).attr("data-order",moment(cellData));
                        }
                    },
                ],
                "initComplete": function(settings, json) {
                    $("#tbody-report-table").show();
                }
            });

        //Date range as a button
        $('#daterange-btn').daterangepicker(
            {
                ranges: {
                    'Hari ini': [moment(), moment()],
                    'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 hari terakhir': [moment().subtract(6, 'days'), moment()],
                    '30 hari terakhir': [moment().subtract(29, 'days'), moment()],
                    'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
                    'Bulan lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment()
            },
            function (start, end) {
                $('#daterange-btn span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
                $('#search-start-date').val(start.format('YYYY-MM-DD'));
                $('#search-end-date').val(end.format('YYYY-MM-DD'));
            }
        );

        $('#filter-btn').click(function(){
            $('#filter-box').toggle();
        });

        $('#btn-filter-search').click(function(){
            var $patient_name = $('#patient-name-filter').val();
            var $gender = $('#patient-gender-filter').val();
            var $age = $('#patient-age-filter').val();
            var $status_diagnose = $('#status-diagnose-filter').val();
            var $visit_type = $('#visit-type-filter').val();
            var $reserve_type =$('#reservation-type-filter').val();


            if($patient_name != "" ){
                $dataTable.columns(0).search($patient_name).draw();
            }else{
                $dataTable.columns(0).search("").draw();
            }

            if($gender != "" ){
                $dataTable.columns(1).search($gender).draw();
            }else{
                $dataTable.columns(1).search("").draw();
            }

            if($status_diagnose != "" ){
                $dataTable.columns(3).search($status_diagnose).draw();
            }else{
                $dataTable.columns(3).search("").draw();
            }

            if($visit_type != "" ){
                $dataTable.columns(4).search($visit_type).draw();
            }else{
                $dataTable.columns(4).search("").draw();
            }

            if($reserve_type != "" ){
                $dataTable.columns(5).search($reserve_type).draw();
            }else{
                $visit_type.columns(5).search("").draw();
            }
        });

        $('#btn-filter-clear').click(function(){
            $("#filter-form")[0].reset();
            $dataTable.search( '' ).columns().search( '' ).draw();
        });

        $.fn.dataTable.ext.search.push(
            function( settings, data, dataIndex ) {
                var min = parseInt( $('#patient-min-age-filter').val(), 10 );
                var max = parseInt( $('#patient-max-age-filter').val(), 10 );
                var age = parseFloat( data[2] ) || 0; // use data for the age column

                if ( ( isNaN( min ) && isNaN( max ) ) ||
                    ( isNaN( min ) && age <= max ) ||
                    ( min <= age   && isNaN( max ) ) ||
                    ( min <= age   && age <= max ) )
                {
                    return true;
                }
                return false;
            }
        );




    });
</script>