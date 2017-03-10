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
            <p>
            <div class="row">
                <div class="col-lg-8">
					<a href="<?php echo site_url("RegisterAdmin/goToAddAdminForm");?>">
						<button type="button" class="btn btn-primary btn-xl" id="add-btn">
							<span class="glyphicon glyphicon-plus"></span>&nbsp Tambah Baru
						</button>
					</a>
                </div>
            </div>
            </p>
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

<?php $this->load->view('admin/modal/modal_add_edit_super_admin_clinic')?>

<script>
    $(function() {
        var $base_url = "<?php echo site_url();?>/";
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
                        var $btn_edit = $("<button>", { class:"btn btn-primary btn-xs edit-btn","type": "button",
                            "data-toggle":"modal","data-target":"#super-admin-clinic-modal-edit-account",
							"data-value": rowData[1],"data-username": rowData[2],"data-email": rowData[3],"data-status": rowData[4]});
                        $btn_edit.append("<span class='glyphicon glyphicon-pencil'></span>&nbsp Edit");

                        var $btn_del = $("<button>", { class:"btn btn-danger btn-xs del-btn","type": "button",
                            "data-value": rowData[1]});
                        $btn_del.append("<span class='glyphicon glyphicon-remove'></span>&nbsp Hapus");

                        var $div_info = $("<div>",{class:"hidden item-info", "data-created":rowData[4],"data-last-modifed":rowData[5]});
                        $(td).html($btn_edit).append(" ").append($btn_del).append($div_info);
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

         //Edit Account Open Modal
        $( "#dataTables-admin tbody" ).on( "click", "button.edit-btn", function() {
            $('#super-admin-clinic-form-edit-account')[0].reset();
            $('.cd-error-message').text("");
			
            var id_item =  $(this).attr("data-value"); //userID
            var username =  $(this).attr("data-username");
            var email =  $(this).attr("data-email");
			var status =  $(this).attr("data-status");
			

            $('#master-username-edit').val(username);
            $('#master-username-edit').attr("data-value",username);

            $('#master-email-edit').val(email);
            $('#master-email-edit').attr("data-value",email);
            $('#master-user-id').val(id_item);
			
			if(status == 1){
                $("#btn-status-active").removeClass("btn-default").addClass("btn-success");
                $("#btn-status-no-active").removeClass("btn-danger").addClass("btn-default");
                $("#master-isactive-edit").val(1);
            }else if(status==0){
                $("#btn-status-active").removeClass("btn-success").addClass("btn-default");
                $("#btn-status-no-active").removeClass("btn-default").addClass("btn-danger");
                $("#master-isactive-edit").val(0);
            }

        });

        //Delete
        $( "#dataTables-admin tbody" ).on( "click", "button.del-btn", function() {
            var id_item =  $(this).attr("data-value");
            var $tr =  $(this).closest("tr");
            var col_title = $tr.find('td').eq(1).text();

            var formData = new FormData();
            formData.append("delID", id_item);

            $(this).deleteData({
                alertMsg     : "Apakah anda ingin menghapus super admin klinik <i><b>"+col_title+"</b></i> ini ?",
                alertTitle   : "Konfirmasi penghapusan",
                url		     : "<?php echo site_url('SuperAdminClinic/deleteSuperAdminClinic')?>",
                data		 : formData,
                locationHref : "<?php echo site_url('SuperAdminClinic')?>"
            });

        });
    });
</script>