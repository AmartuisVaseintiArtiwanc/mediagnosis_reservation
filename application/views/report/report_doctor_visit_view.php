<?php $this->load->helper('HTML');
?>
<!-- daterange picker -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/daterangepicker/daterangepicker-bs3.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/datepicker/datepicker3.css">
<style>
    .hidden{
        display: none;
    }
    th.dt-center, td.dt-center { text-align: center; }
    td.dt-right{  text-align: right;  }
</style>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Laporan Kunjungan tiap Dokter -
        <span class="date-filter"></span>
    </h1>
    <ol class="breadcrumb">
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="box" id="content-container" >
        <div class="box-body">
            <p>
                <div class="row">
                    <div class="col-lg-8">
                        <button type="button" class="btn btn-primary btn-xl" id="filter-btn">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp Filter
                        </button>
                    </div>
                </div>
            </p>
            <table class="table table-bordered table-striped tbl-master" id="report-table">
                <thead>
                <tr>
                    <th style = "text-align:left;">Dokter</th>
                    <th style = "text-align:right;">Total Kunjungan</th>
                    <th style = "text-align:center;">Option</th>
                </tr>
                </thead>

                <tbody>
                    <?php foreach($report_data as $row){?>
                    <tr>
                        <td><?php echo $row['doctorName'];?></td>
                        <td class="dt-right"><?php echo $row['totalVisit'] != "" ? $row['totalVisit']: 0 ;?></td>
                        <td class="dt-center">
                            <a href="<?php echo site_url()."/Report/reportPatientTypeDetail/".$row['doctorID']."/".$start_date."/".$end_date;?>">
                                <button type="button" class="btn btn-primary btn-xs">
                                    <span class="glyphicon glyphicon-plus"></span>&nbsp Detail
                                </button>
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div>

    <!-- MODAL -->
    <div class="modal fade search-date-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="modal-title">Search by Date</h4>
                </div>
                <div class="modal-body">
                    <form id="search-form">
                        <div class="form-group">
                            <label>Date range button:</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                                    <span>
                                      <i class="fa fa-calendar"></i> Pilih Tanngal Pencarian
                                    </span>
                                    <i class="fa fa-caret-down"></i>
                                </button>
                            </div>
                            <input type="hidden" id="search-start-date">
                            <input type="hidden" id="search-end-date">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-search" data-search="">Search</button>
                </div>
            </div>
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

        setFilterDate();
        function setFilterDate(){
            var $start_date = "<?php echo $start_date;?>";
            var $end_date = "<?php echo $end_date;?>";
            $start_date = moment($start_date).format('D MMMM YYYY');
            $end_date = moment($end_date).format('D MMMM YYYY');

            if($start_date == $end_date){
                $(".date-filter").html("("+$start_date+")");
            }else{
                $(".date-filter").html("("+$start_date+"-"+$end_date+")");
            }
        }

        var table = $('#report-table').DataTable({
            "lengthChange": false,
            columns: [
                { data: 0,"width": "40%" },
                { data: 1, "width": "40%"},
                { data: 2, "width": "20%"}
            ]
        });

        $('#filter-btn').click(function(){
            $('#modal-title').text("Search by Date");
            $('.search-date-modal').modal('show');
            $('#btn-search').attr("data-search","date");
        });

        // Button Search on Click
        $('#btn-search').click(function(){
            var $start = $('#search-start-date').val();
            var $end = $('#search-end-date').val();
            var $url = "<?php echo site_url();?>"+"/Report/reportDoctorVisit?";
            $url+="from="+$start;
            $url+="&to="+$end;

            location.href = $url;
        });

        var start = moment();
        var end = moment();
        function cb(start, end) {
            $('#daterange-btn span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
        }
        cb(start, end);
        //Date range as a button
        $('#daterange-btn').daterangepicker(
            {
                startDate: start,
                endDate: end,
                "opens": "center",
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            },
            function (start, end) {
                $('#daterange-btn span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
                $('#search-start-date').val(start.format('YYYY-MM-DD'));
                $('#search-end-date').val(end.format('YYYY-MM-DD'));
            },cb
        );
    });
</script>