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

    #dataTables-diagnose tbody tr.selected{
        background-color: #3c8dbc;
        color: #FFF;
    }
    input[type=checkbox] {
        transform: scale(1.5);
    }
    .symptomp-checked-container{
        position: fixed;
        top: 50%;
        right: 0;
    }
    .btn-app>.badge{
        left: -10px;
        right: inherit;
        font-size: 16px;
    }

</style>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Master
        <small>Disease</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Transaction</a></li>
        <li class="active">Diagnose</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="box" id="content-container" >
        <div class="box-header">
            <h3 class="box-title">Gejala</h3>
        </div>

        <div class="box-body">
            <p>
            <div class="row">
                <div class="col-lg-8">
                    <button type="button" class="btn btn-primary btn-xl" id="btn-start-diagnose">
                        <span class="glyphicon glyphicon-plus"></span>&nbsp Mulai Diagnosa
                    </button>
                </div>
            </div>
            </p>
            <table  class="table table-bordered table-striped table-hover tbl-master" id="dataTables-diagnose">
                <thead>
                <tr>
                    <th style = "text-align:left;">Gejala</th>
                    <th style = "text-align:center;">Pilih</th>
                </tr>
                </thead>

                <tbody>

                </tbody>
            </table>

        </div>

        <div class="symptomp-checked-container">
            <a class="btn btn-app" id="btn-open-modal-symptomp">
                <span class="badge bg-red" id="span-count-symptomp" data-count=0></span>
                <i class="fa fa-heart-o"></i> Gejala
            </a>
        </div>
    </div>
</section>
<?php $this->load->view('admin/modal/modal_symptomp_diagnose_view')?>

<script>
    $(function() {
        var $base_url = "<?php echo site_url();?>/";
        var $selected = [];
        var $table_list_symptomp="";
        var $modal_table_symptomp="";

        getData();
        function getData(){
            // Quickly and simply clear a table
            $('#dataTables-diagnose').dataTable().fnClearTable();
            // Restore the table to it's original state in the DOM by removing all of DataTables enhancements, alterations to the DOM structure of the table and event listeners
            $('#dataTables-diagnose').dataTable().fnDestroy();

            $.ajax({
                url: $base_url+"Diagnose/getSymptompData",
                type: "POST",
                dataType: 'json',
                cache:false,
                success:function(data){
                    renderAdminData(data);
                },
                error: function(xhr, status, error) {
                    //var err = eval("(" + xhr.responseText + ")");
                    //alertify.error(xhr.responseText);
                    alertify.error("Cannot response server !");
                }
            });
        }

        function renderAdminData($data){
            //alert(JSON.stringify($data));
            $table_list_symptomp = $('#dataTables-diagnose').DataTable( {
                "aaData": $data,
                "lengthChange": false,
                "pageLength": 25,
                "autoWidth": false,
                deferRender: true,
                "aoColumns": [
                    { "mDataProp": "symptompName"},
                    { "mDataProp": "symptompID"}
                ],
                "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                    $(nRow).attr('id', "tr-symptomp-"+aData.symptompID);
                },
                "columnDefs": [
                    {
                        "width": "80%",
                        "targets": 0
                    },
                    {
                        "width": "20%",
                        "targets": [ -1 ], //last column
                        "searchable": false,
                        "orderable": false,//set not orderable
                        "className": "dt-center",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            /*
                            var $btn_add = $("<button>", { class:"btn btn-primary btn-xs add-admin-btn","type": "button",
                                "data-value": cellData, "data-name": rowData.symptompName });
                            $btn_add.append("<span class='glyphicon glyphicon-plus'></span>&nbsp PILIH");
                            $(td).html($btn_add);
                            */
                            var $div = $("<div>",{class : "checkbox icheck"});
                            var $label = $("<label>");
                            var $checkbox = $("<input>", { "name":"id[]","type": "checkbox",
                                "value": cellData, "data-name": rowData.symptompName });

                            $checkbox.appendTo($label);
                            $label.appendTo($div);
                            $(td).html($div);
                        }
                    },
                ]
            } );
        }

        // Handle click on checkbox to set state of "Select all" control
        $('#dataTables-diagnose tbody').on('click', 'input[type="checkbox"]', function(){
            var $row = $(this).closest('tr');
            var $symptomp_id = $(this).val();
            var $symptomp_name = $(this).attr("data-name");

            var $symptomp = {
                symptompID : $symptomp_id,
                symptompName : $symptomp_name
            };

            if(this.checked){
                $row.addClass('selected');
                updateCountSymptomp(1);
                $selected.push($symptomp);

            } else {
                $row.removeClass('selected');
                updateCountSymptomp(-1);
                removeSymptomp($symptomp_id);
            }
        });

        $("#btn-open-modal-symptomp").click(function(){
            renderSelectedSymptomp($selected);
            $('#symptomp-diagnose-modal').modal("show");
        });

        function removeSymptomp($value){
            $selected = $selected.filter(function(el) {
                return el.symptompID !== $value;
            });
        }

        function renderSelectedSymptomp($data){
            $modal_table_symptomp = $('#dataTables-symptomp').DataTable( {
                "aaData": $data,
                "lengthChange": false,
                "pageLength": 10,
                "autoWidth": false,
                deferRender: true,
                "aoColumns": [
                    { "mDataProp": "symptompName"},
                    { "mDataProp": "symptompID"}
                ],
                "columnDefs": [
                    {
                        "width": "80%",
                        "targets": 0
                    },
                    {
                        "width": "20%",
                        "targets": [ -1 ], //last column
                        "searchable": false,
                        "orderable": false,//set not orderable
                        "className": "dt-center",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            var $btn_remove = $("<button>", { class:"btn btn-danger btn-xs remove-symptomp-btn","type": "button",
                                "data-value": cellData, "data-name": rowData.symptompName });
                            $btn_remove.append("<span class='glyphicon glyphicon-remove'></span>&nbsp HAPUS");
                            $(td).html($btn_remove);
                        }
                    },
                ]
            } );
        }

        $('#dataTables-symptomp tbody').on('click', 'button.remove-symptomp-btn', function () {
            var $tr = $(this).closest("tr");
            var $id =  $(this).attr("data-value");
            var $name =  $(this).attr("data-name");
            var $diagnose_row = $("#tr-symptomp-"+$id);

            // Remove from dataTables
            $modal_table_symptomp.row( $(this).parents('tr') ).remove().draw();

            // Update Display Count Button
            updateCountSymptomp(-1);

            // Update Display on Table Diagnose Symptomp
            $diagnose_row.removeClass('selected');
            $diagnose_row.find('input[type="checkbox"]').prop('checked', false);

            // Remove it from Array
            removeSymptomp($id);
        });

        function updateCountSymptomp($value){
            var $count_symptomp_container = $("#span-count-symptomp");
            var $count_symptomp_value = $count_symptomp_container.attr("data-count");

            $count_symptomp_value = parseFloat($count_symptomp_value) + $value;
            $count_symptomp_container.attr("data-count",$count_symptomp_value);
            $count_symptomp_container.html($count_symptomp_value);
        }

        $('#symptomp-diagnose-modal').on('hidden.bs.modal', function () {
            //$('#dataTables-symptomp').html("");
            $('#dataTables-symptomp').dataTable().fnDestroy();
        })
    });
</script>
