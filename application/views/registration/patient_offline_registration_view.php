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
        Manual
        <small>Patient</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Manual</a></li>
        <li class="active">Patient</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="box" id="content-container" >
        <div class="box-header">
            <h3 class="box-title">Patient List</h3>
        </div>

        <div class="box-body">
            <p>
            <div class="row">
                <div class="col-lg-8">
                    <button type="button" class="btn btn-primary btn-xl" id="add-btn"
                            data-toggle="modal" data-target="#patient-modal-add">
                        <span class="glyphicon glyphicon-plus"></span>&nbsp Add New
                    </button>
                </div>
            </div>
            </p>
            <table  class="table table-bordered table-striped tbl-master" id="dataTables-list">
                <thead>
                <tr>
                    <th>No</th>
                    <th style = "text-align:left;">Patient Name</th>
                    <th style = "text-align:center;">No. KTP</th>
                    <th style = "text-align:center;">No. BPJS</th>
                    <th style = "text-align:center;">isActive</th>
                </tr>
                </thead>

                <tbody>

                </tbody>
            </table>

        </div>
    </div>
</section>

<?php $this->load->view('modal/modal_add_edit_patient')?>

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
                "url": baseurl+"Register/dataPatientListAjax",
                "type": "POST"
            },
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                $(nRow).attr('id', aData[1]);
            },
            columns: [
                { data: 0,"width": "10%" },
                { data: 2, "width": "40%"},
                { data: 3, "width": "20%"},
                { data: 4, "width": "20%"},
                { data: 5, "width": "10%"}
            ],
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [4], //thrid column
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

        /*$('#dataTables-list tbody').on('click', 'tr', function () {
            var id = this.id;
            var index = $.inArray(id, selected);

            if ( index === -1 ) {
                selected.push( id );
            } else {
                selected.splice( index, 1 );
            }

            var count_selected = selected.length;
            $("#dataTables-list_info span").empty();
            $("#dataTables-list_info").append(" <span>"+count_selected+" selected</span>");

            $(this).toggleClass('selected');
        } );*/

        $('#patient-modal-add').on('shown.bs.modal', function () {
            $('#patient-form-add')[0].reset();
            $('#modal-title-add').text("Add New Patient");
            $('#err-master-name-add').text("");
            $('#master-name-add').focus();
        })

    });
</script>