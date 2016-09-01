<style>
    .dt-center{
        text-align: center;
    }
</style>
<script type="text/javascript">
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({
        placement : 'right',
        html:true
    });
});
</script>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Penjualan
        <small>List</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Penjualan</a></li>
        <li class="active">List</li>
    </ol>
</section>

<section class="content">
    <div class="box" id="content-container" >
        <div class="box-header">
            <h3 class="box-title">Penjualan List</h3>
        </div>

        <div class="box-body">
            <p>
            <div class="row">
                <div class="col-lg-8">
                    <a class="cd-signin main-nav" href="<?php echo site_url('penjualan/goToAddNewPenjualan')?>">
                        <button type="button" class="btn btn-primary btn-xl">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp Add New Penjualan
                        </button>
                    </a>
                </div>
            </div>
            </p>
            <table class="table table-bordered table-striped tbl-master" id="dataTables-list">
                <thead>
                <tr>
                    <th style = "text-align:center;font-weight: bold;">NO</th>
                    <th style = "font-weight: bold;">Kode Bon</th>
                    <th style = "font-weight: bold;">Tanggal Penjualan</th>
                    <th style = "font-weight: bold;">Pembeli</th>
                    <th style = "font-weight: bold;">Status</th>
                    <th style = "text-align:center;font-weight: bold;">Option</th>
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
        var table = $('#dataTables-list').DataTable({
            "lengthChange": false,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "autoWidth": false,
            deferRender: true,
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": baseurl+"Penjualan/dataPenjualanListAjax",
                "type": "POST"
            },
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                $(nRow).attr('data-value', aData[1]);
            },
            columns: [
                { data: 0,"width": "5%" },
                { data: 2, "width": "20%"},
                { data: 3, "width": "20%"},
                { data: 4, "width": "20%"},
                { data: 5, "width": "10%"},
                { data: 6, "width": "25%"}
            ],
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [ -1 ], //last column
                    "orderable": false,//set not orderable
                    "className": "dt-center",
                    "createdCell": function (td, cellData, rowData, row, col) {
                        var ahref_edit = $("<a>", {href: "<?php echo site_url('Penjualan/goToEditPenjualan');?>/"+cellData});
                        var $btn_edit = $("<button>", { class:"btn btn-primary btn-xs edit-btn","type": "button",
                            "data-value": cellData});
                        $btn_edit.append("<span class='glyphicon glyphicon-pencil'></span>&nbsp Edit");
                        ahref_edit.append($btn_edit);

                        var $btn_del = $("<button>", { class:"btn btn-danger btn-xs del-btn","type": "button",
                            "data-value": cellData});
                        $btn_del.append("<span class='glyphicon glyphicon-remove'></span>&nbsp Delete");

                        var $div_info = $("<div>",{class:"hidden item-info", "data-created":rowData[3],"data-last-modifed":rowData[4]});
                        $(td).html(ahref_edit).append(" ").append($btn_del).append($div_info);
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

        $('#dataTables-list tbody').on('click', 'button.del-btn', function () {
            var id_item =  $(this).attr("data-value");
            var $tr =  $(this).closest("tr");
            var col_title = $tr.find('td').eq(1).text();

            var formData = new FormData();
            formData.append("delID", id_item);

            $(this).deleteData({
                alertMsg     : "Do you want to delete this <i><b>"+col_title+"</b></i> Penjualan ?",
                alertTitle   : "Delete Confirmation",
                url		     : "<?php echo site_url('Penjualan/deletePenjualan')?>",
                data		 : formData,
                locationHref : "<?php echo site_url('Penjualan')?>"
            });
        });
    });
</script>