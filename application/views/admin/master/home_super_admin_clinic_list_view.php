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
</style>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Master
        <small>Super Admin Klinik</small>
        - <?php echo $master_title;?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Master</a></li>
        <li class="active">Super Admin Klinik</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="box" id="content-container" >
        <div class="box-header">
            <h3 class="box-title">Daftar Super Admin Klinik</h3>
        </div>

        <div class="box-body">
            <table  class="table table-bordered table-striped tbl-master" id="dataTables-admin">
                <thead>
                <tr>
                    <th>No</th>
                    <th style = "text-align:left;">Username</th>
                    <th style = "text-align:left;">Email</th>
                    <th style = "text-align:center;">Status</th>
                    <th style = "text-align:left;display:none;">Created</th>
                    <th style = "text-align:left;display:none;">Created By</th>
                    <th style = "text-align:left;display:none;">Last Modified</th>
                    <th style = "text-align:left;display:none;">Last Modified By</th>
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
        var $master =  "<?php echo $master;?>";
        var selected = [];
		
		$(".sidebar-menu").find(".active").removeClass("active");
		$(".mediagnosis-navigation-master").addClass("active");

        var table = $('#dataTables-admin').DataTable({
            "lengthChange": false,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "autoWidth": false,
            deferRender: true,
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": $base_url+"SuperAdminClinic/dataSuperAdminClinicListAjax",
                "type": "POST"
            },
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                $(nRow).attr('id', aData[1]);
            },
            columns: [
                { data: 0,"width": "10%" },
                { data: 2, "width": "30%"},
                { data: 3, "width": "30%"},
                { data: 4, "width": "10%"},
                { data: 5, "width": "10%"}
            ],
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [ -1 ], //last column
                    "orderable": false,//set not orderable
                    "className": "dt-center",
                    "createdCell": function (td, cellData, rowData, row, col) {
                        var $btn_edit = $("<button>", { class:"btn btn-primary btn-xs btn-detail","type": "button",
                            "data-value": rowData[1]});
                        $btn_edit.append("<span class='glyphicon glyphicon-search'></span>&nbsp Detail "+$master);
                        $(td).html($btn_edit);
                    }
                },
                {
                    "targets": [0], //last column
                    "orderable": false//set not orderable}
                },
                {
                    "targets": [3], //thrid column
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
                }
            ],
            "rowCallback": function( row, data ) {
                if ( $.inArray(data[1], selected) !== -1 ) {
                    $(row).addClass('selected');
                }
            }

        });

        //Delete
        $( "#dataTables-admin tbody" ).on( "click", "button.btn-detail", function() {
            var $id_item =  $(this).attr("data-value");
            // ex : $base_url+"Doctor/index/"+$id_item
            var $url = $base_url+$master+"/index/"+$id_item

            window.setTimeout( function(){
                window.open($url,'_blank');
            }, 500);

        });
    });
</script>