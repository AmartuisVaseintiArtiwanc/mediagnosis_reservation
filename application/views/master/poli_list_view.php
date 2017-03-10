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
         <small>Poli</small>
     </h1>
     <ol class="breadcrumb">
         <li><a href="#"><i class="fa fa-dashboard"></i> Master</a></li>
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
                <div class="row">
                    <div class="col-lg-8">
                        <button type="button" class="btn btn-primary btn-xl" id="add-btn"
                                data-toggle="modal" data-target="#poli-modal-add">
                            <span class="glyphicon glyphicon-plus"></span>&nbsp Tambah Poli Baru
                        </button>
                    </div>
                </div>
            </p>
            <table class="table table-bordered table-striped tbl-master" id="dataTables-list">
                <thead>
                    <tr>
                        <th>No</th>
                        <th style = "text-align:left;">Poli</th>
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

<?php $this->load->view('modal/modal_add_edit_poli')?>

 <script>
     $(function() {
         var baseurl = "<?php echo site_url();?>/";
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
                 "url": baseurl+"Poli/dataPoliListAjax",
                 "type": "POST"
             },
             "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                 $(nRow).attr('id', aData[1]);
             },
             columns: [
                 { data: 0,"width": "10%" },
                 { data: 2, "width": "40%"},
                 { data: 3, "width": "10%"},
                 { data: 4, "width": "40%"}
             ],
             //Set column definition initialisation properties.
             "columnDefs": [
                 {
                     "targets": [ -1 ], //last column
                     "orderable": false,//set not orderable
                     "className": "dt-center",
                     "createdCell": function (td, cellData, rowData, row, col) {
                         var $btn_edit = $("<button>", { class:"btn btn-primary btn-xs edit-btn","type": "button",
                             "data-toggle":"modal","data-target":"#poli-modal-edit","data-value": rowData[1]});
                         $btn_edit.append("<span class='glyphicon glyphicon-pencil'></span>&nbsp Edit");

                         var $btn_del = $("<button>", { class:"btn btn-danger btn-xs del-btn","type": "button",
                            "data-value": rowData[1]});
                         $btn_del.append("<span class='glyphicon glyphicon-remove'></span>&nbsp Hapus");

                         var $div_info = $("<div>",{class:"hidden item-info", "data-created":rowData[4],"data-last-modifed":rowData[5]});
                         $(td).html($btn_edit).append(" ").append($btn_del).append($div_info);
                     }
                 },
                 {
                     "targets": [0], //first column
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
                 }
             ],
             "rowCallback": function( row, data ) {
                 if ( $.inArray(data[1], selected) !== -1 ) {
                     $(row).addClass('selected');
                 }
             }

         });

         $('#dataTables-list tbody').on('click', 'tr', function () {
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
         } );

         $('#poli-modal-add').on('shown.bs.modal', function () {
             $('#Poli-form-add')[0].reset();
             $('#modal-title-add').text("Tambah Poli Baru");
             $('#err-master-name-add').text("");
             $('#master-name-add').focus();
         })

         //Edit open Modal
         $( "#dataTables-list tbody" ).on( "click", "button.edit-btn", function() {
             $('#poli-form-edit')[0].reset();
             $('#err-master-name-edit').text("");

             var id_item =  $(this).attr("data-value");
             var $tr =  $(this).closest("tr");
             var $td =  $(this).closest("td");
             var text = $tr.find('td').eq(1).text();
             var status = $tr.find('td span.status-label').attr("data-status");
             var created = $td.find('div.item-info').attr("data-created");
             var last_modified = $td.find('div.item-info').attr("data-last-modifed");

             $('#modal-title-edit').html("Edit Poli - <b>"+text+"</b>");
             $('#master-name-edit').val(text);
             $('#master-id').val(id_item);

             if(status == 1){
                 $("#btn-status-active").removeClass("btn-default").addClass("btn-success");
                 $("#btn-status-no-active").removeClass("btn-danger").addClass("btn-default");
                 $("#master-isactive-edit").val(1);
             }else if(status==0){
                 $("#btn-status-active").removeClass("btn-success").addClass("btn-default");
                 $("#btn-status-no-active").removeClass("btn-default").addClass("btn-danger");
                 $("#master-isactive-edit").val(0);
             }

             $('#created').empty();
             $('#created').append("Created : "+"<b>"+created+"</b>");
             $('#last_modified').empty();
             $('#last_modified').append("Last Modified : "+"<b>"+last_modified+"</b>");

         });

         //Delete
         $( "#dataTables-list tbody" ).on( "click", "button.del-btn", function() {
             var id_item =  $(this).attr("data-value");
             var $tr =  $(this).closest("tr");
             var col_title = $tr.find('td').eq(1).text();

             var formData = new FormData();
             formData.append("delID", id_item);

             $(this).deleteData({
                 alertMsg     : "Apakah anda ingin menghapus poli <i><b>"+col_title+"</b></i> ini ?",
                 alertTitle   : "Konfirmasi Penghapusan",
                 url		     : "<?php echo site_url('Poli/deletePoli')?>",
                 data		 : formData,
                 locationHref : "<?php echo site_url('Poli')?>"
             });

         });
     });
 </script>