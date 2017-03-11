<?php $this->load->helper('HTML');
?>
<style>
    .cd-error-message{
        font-size:12px;
        visibility: visible;
    }
    .hidden{
        display: none;
    }
    th.dt-center, td.dt-center { text-align: center; }

    th.dt-right, td.dt-right{text-align: right;}
</style>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Master
        <small>Klinik</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Master</a></li>
        <li class="active">Klinik</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="box" id="content-container" >
        <div class="box-header">
            <h3 class="box-title">Daftar Klinik</h3>
        </div>

        <div class="box-body">
            <p>
            <div class="row">
                <div class="col-lg-8">
                    <button type="button" class="btn btn-primary btn-xl" id="btn-update-clinic">
                        <span class="glyphicon glyphicon-open"></span>&nbsp Update Rating Klinik
                    </button>
                </div>
            </div>
            </p>
            <table  class="table table-bordered table-striped tbl-master" id="dataTables-list">
                <thead>
                <tr>
                    <th>No</th>
                    <th style = "text-align:left;">Klinik</th>
                    <th style = "text-align:center;">Status</th>
                    <th style = "text-align:center;">Super Admin</th>
                    <th style = "text-align:right;">Rating</th>
                    <th style = "text-align:center;">Tanggal Update</th>
                </tr>
                </thead>

                <tbody>

                </tbody>
            </table>

        </div>
    </div>
</section>

<!--Modal ADD-->
<div class="modal fade" id="loading-modal" tabindex="-1" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>Harap Tunggu</b></h4>
            </div><!--modal header-->

            <div class="modal-body">
                <div class="box">
                    <h3 class="text-center">Memproses Update</h3>
                    <br/>
                    <div class="overlay loading-screen-queue">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>

            </div><!--modal body-->

            <div class="modal-footer">
            </div><!--modal footer-->

        </div><!--modal content-->
    </div><!--modal dialog-->
</div>

<script>
    $(function() {
        var $base_url = "<?php echo site_url();?>/";
        var selected = [];

        $(".sidebar-menu").find(".active").removeClass("active");
        $(".mediagnosis-navigation-master").addClass("active");

        var table = $('#dataTables-list').DataTable({
            "lengthChange": false,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "autoWidth": false,
            deferRender: true,
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": $base_url+"Rating/dataRatingClinicListAjax",
                "type": "POST"
            },
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                $(nRow).attr('id', aData[1]);
            },
            columns: [
                { data: 0,"width": "10%" },
                { data: 2, "width": "20%"},
                { data: 3, "width": "10%"},
                { data: 4, "width": "20%"},
                { data: 5, "width": "10%"},
                { data: 6, "width": "20%"},
            ],
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [0], //last column
                    "orderable": false//set not orderable}
                },
                {
                    "targets": [2], //thrid column
                    "className": "dt-center",
                    "createdCell": function (td, cellData, rowData, row, col) {

                        var $active = $("<span>", { class:"badge bg-green status-label","data-status":1}).html("ACTIVE");
                        var $no_active = $("<span>", { class:"badge bg-red status-label","data-status":0}).html("NO ACTIVE");
                        if(cellData==1){
                            $(td).html($active);
                        }else if(cellData==0){
                            $(td).html($no_active)
                        }
                    }
                },
                {
                    "targets": [3], //last column
                    "className": "dt-center"
                },
                {
                    "targets": [4], //last column
                    "className": "dt-right"
                },
                {
                    "targets": [5], //last column
                    "className": "dt-center"
                },
            ],
            "rowCallback": function( row, data ) {
                if ( $.inArray(data[1], selected) !== -1 ) {
                    $(row).addClass('selected');
                }
            }

        });

        $('#btn-update-clinic').click(function () {
            alertify.confirm("Apakah Anda yakin untuk Meng-Update Rating Klinik ?",function(){
                $.ajax({
                    url: $base_url+"Rating/doUpdateClinic",
                    type: "POST",
                    dataType: 'json',
                    cache:false,
                    beforeSend:function(){
                        //SHOW LOADING SCREEN
                        $("#loading-modal").modal("show");
                    },
                    success:function(data){
                        if(data.status != "error"){
                            alertify.success(data.msg);
                            window.setTimeout( function(){
                                $("#loading-modal").modal("hide");
                                location.href = $base_url+"Rating/ratingClinicList";
                            }, 1500 );

                        }else{
                            alertify.error("Failed to Update Rating !");
                            $("#loading-modal").modal("hide");
                        }

                    },
                    error: function(xhr, status, error) {
                        //var err = eval("(" + xhr.responseText + ")");
                        //alertify.error(xhr.responseText);
                        alertify.error("Cannot response server !");
                    }
                });
            }).setHeader("Konfirmasi Update");

        });
    });
</script>