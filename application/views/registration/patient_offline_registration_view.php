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
        Administrasi Manual
        <small>Pendaftaran Manual</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Administrasi Manual</a></li>
        <li class="active">Pendaftaran Manual</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="box" id="content-container" >
        <div class="box-header">
            <h3 class="box-title">Daftar Pasien</h3>
        </div>

        <div class="box-body">
            <p>
            <div class="row">
                <div class="col-lg-8">
                    <button type="button" class="btn btn-primary btn-xl" id="add-btn"
                            data-toggle="modal" data-target="#patient-modal-add">
                        <span class="glyphicon glyphicon-plus"></span>&nbsp Tambah Baru
                    </button>
                </div>
            </div>
            </p>
            <table  class="table table-bordered table-striped tbl-master" id="dataTables-list">
                <thead>
                <tr>
                    <th>No</th>
                    <th style = "text-align:left;">Nama Pasien</th>
                    <th style = "text-align:center;">No. KTP</th>
                    <th style = "text-align:center;">No. BPJS</th>
					<th style = "text-align:center;">Nomor MRIS</th>
                    <th style = "text-align:center;">Option</th>
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
		
		$(".sidebar-menu").find(".active").removeClass("active");
		$(".mediagnosis-navigation-manual").addClass("active");
		
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
                $(nRow).attr('data-patient-name', aData[2]);
                $(nRow).attr('data-ktp', aData[3]);
                $(nRow).attr('data-bpjs', aData[4]);
                $(nRow).attr('data-mris', aData[5]);
                $(nRow).attr('data-gender', aData[7]);
                $(nRow).attr('data-participant-status', aData[8]);
                $(nRow).attr('data-participant-type', aData[9]);

            },
            columns: [
                { data: 0,"width": "10%" },
                { data: 2, "width": "30%"},
                { data: 3, "width": "20%"},
                { data: 4, "width": "20%"},
				{ data: 5, "width": "10%"},
                { data: 7, "width": "10%"}
            ],
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [ -1 ], //last column
                    "orderable": false,//set not orderable
                    "className": "dt-center",
                    "createdCell": function (td, cellData, rowData, row, col) {
                        var $btn_edit = $("<button>", { class:"btn btn-primary btn-xs edit-btn","type": "button",
                            "data-toggle":"modal","data-target":"#patient-modal-edit","data-value": rowData[1]});
                        $btn_edit.append("<span class='glyphicon glyphicon-pencil'></span>&nbsp Edit");

                        $(td).html($btn_edit);
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
            $('#modal-title-add').text("Tambah Pasien Baru");
            $('#err-master-name-add').text("");
            $('#master-name-add').focus();
        })

        //Edit open Modal
        $( "#dataTables-list tbody" ).on( "click", "button.edit-btn", function() {
            $('#patient-form-edit')[0].reset();
            $('#patient-form-edit .cd-error-message').text("");

            var patient_id =  $(this).attr("data-value");
            var $tr =  $(this).closest("tr");
            var $patient_name =  $($tr).attr("data-patient-name");
            var $ktp =  $($tr).attr("data-ktp");
            var $bpjs =  $($tr).attr("data-bpjs");
            var $mris =  $($tr).attr("data-mris");
            var $gender =  $($tr).attr("data-gender");
            var $participant_status =  $($tr).attr("data-participant-status");
            var $participant_type =  $($tr).attr("data-participant-type");

            $('#patient-id-edit').val(patient_id);
            $('#patient-name-edit').val($patient_name);
            $('#no-ktp-edit').val($ktp);
            $('#no-bpjs-edit').val($bpjs);
            $('#gender-edit').val($gender);
            $('#participant-status-edit').val($participant_status);
            $('#participant-type-edit').val($participant_type);

        });

    });
</script>