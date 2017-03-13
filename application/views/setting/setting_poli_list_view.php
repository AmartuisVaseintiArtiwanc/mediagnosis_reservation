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
        Setting
        <small>Poli</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Setting</a></li>
        <li class="active">Poli</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="box" id="content-container" >
        <div class="box-header">
            <h3 class="box-title">Daftar Poli</h3>
        </div>

        <div class="box-body">
            <p>
            </p>
            <table  class="table table-bordered table-striped tbl-master" id="dataTables-list">
                <thead>
                <tr>
                    <th>No</th>
                    <th style = "text-align:left;">Klinik</th>
                    <th style = "text-align:left;">Poli</th>
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
        var baseurl = "<?php echo site_url();?>/";
        var selected = [];
		
		$(".sidebar-menu").find(".active").removeClass("active");
		$(".mediagnosis-navigation-setting").addClass("active");
		
        var table = $('#dataTables-list').DataTable({
            "lengthChange": false,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "autoWidth": false,
            deferRender: true,
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": baseurl+"SPoli/dataSPoliListAjax",
                "type": "POST"
            },
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                $(nRow).attr('id', aData[1]);
            },
            columns: [
                { data: 0,"width": "10%" },
                { data: 2, "width": "40%"},
                { data: 3, "width": "40%"},
                { data: 4, "width": "10%"},
            ],
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [ -1 ], //last column
                    "orderable": false,//set not orderable
                    "className": "dt-center",
                    "createdCell": function (td, cellData, rowData, row, col) {
                        var $btn_edit = $("<button>", { class:"btn btn-primary btn-xs edit-btn",
                            "type": "button", "data-clinic": rowData[4], "data-poli": rowData[5],"data-value":rowData[1]});
                        $btn_edit.append("<span class='glyphicon glyphicon-pencil'></span>&nbsp Edit");
                        $(td).html($btn_edit).append(" ");
                    }
                },
                {
                    "targets": [0], //last column
                    "orderable": false//set not orderable}
                }
            ],
            "rowCallback": function( row, data ) {
                if ( $.inArray(data[1], selected) !== -1 ) {
                    $(row).addClass('selected');
                }
            }

        });       

        //Edit open Modal
        $( "#dataTables-list tbody" ).on( "click", "button.edit-btn", function() {
            var id_item =  $(this).attr("data-value");
			location.href = baseurl+"SPoli/goToSettingDetailPoli/"+id_item;
        });       
    });
</script>