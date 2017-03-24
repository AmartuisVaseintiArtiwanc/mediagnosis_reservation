<?php $this->load->helper('HTML');
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Transaksi
        <small>Chat Bermasalah</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Transaksi</a></li>
        <li class="active">Chat Bermasalah</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="box" id="content-container" >
        <div class="box-header">
            <h3 class="box-title">Daftar Chat Yang bermasalah</h3>
        </div>

        <div class="box-body">
            <table  class="table table-bordered table-striped tbl-master" id="dataTables-admin">
                <thead>
                <tr>
                    <th>No</th>
                    <th style = "text-align:left;">Nama Pasien</th>
                    <th style = "text-align:left;">Nama Dokter</th>
					<th style = "text-align:left;">Nama Topik</th>
					<th style = "text-align:left;">Entry Chat Terakhir</th>
					<th style = "text-align:left;">ID Ruangan</th>
                    <th style = "text-align:center;">Status</th>
                    <th style = "text-align:left;display:none;">Created</th>
                    <th style = "text-align:left;display:none;">Created By</th>
                    <th style = "text-align:left;display:none;">Last Modified</th>
                    <th style = "text-align:left;display:none;">Last Modified By</th>
                </tr>
                </thead>

                <tbody>

                </tbody>
            </table>

        </div>
    </div>
</section>
<script>
	$(function(){
		var $base_url = "<?php echo site_url();?>/";
		
		$(".sidebar-menu").find(".active").removeClass("active");
		$(".mediagnosis-navigation-transaction").addClass("active");
		
		var table = $('#dataTables-admin').DataTable({
            "lengthChange": false,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "autoWidth": false,
            deferRender: true,
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": $base_url+"Troubleshoot/dataReportedChatListAjax",
                "type": "POST"
            },
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                $(nRow).attr('id', aData[1]);
            },
            columns: [
                { data: 0,"width": "10%" },
                { data: 2, "width": "20%"},
                { data: 3, "width": "20%"},
                { data: 4, "width": "10%"},
                { data: 5, "width": "10%"},
				{ data: 6, "width": "10%"},
				{ data: 7, "width": "10%"}
            ],
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [0], //last column
                    "orderable": false//set not orderable}
                },
                {
                    "targets": [6], //thrid column
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
            ]         

        });
		
		
	});
</script>
