<!--Modal ADD-->
<div class="modal fade" id="lookup-symptomp-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Master Symptomp</b></h4>
            </div><!--modal header-->

            <div class="modal-body">
                <table class="table table-hover table-bordered table-striped" id="dataTables-symptomp">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th style = "text-align:left;">Nama Symptomp</th>
                        <th style = "text-align:center;">Option</th>
                    </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>
            </div><!--modal body-->

            <div class="modal-footer">
            </div><!--modal footer-->

        </div><!--modal content-->
    </div><!--modal dialog-->
</div>

<script>

    $(document).ready( function() {
        var baseurl = "<?php echo site_url();?>/";
        var selected = [];
        var table = "";

        $("#lookup-symptomp-btn").click(function(){
            table = $('#dataTables-symptomp').DataTable({
                "lengthChange": false,
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "order": [], //Initial no order.
                "autoWidth": false,
                deferRender: true,
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": baseurl+"symptomp/dataSymptompListAjax",
                    "type": "POST"
                },
                "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                    $(nRow).attr('data-id', aData[1]);
                },
                columns: [
                    { data: 0,"width": "10%" },
                    { data: 2, "width": "70%"},
                    { data: 1, "width": "20%"}
                ],
				"fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
                    symptompLookupData.splice(0, symptompLookupData.length);
                    var item=[];
                    var i=0;
                    while(item = aData[i++]){
                        symptompLookupData.push(item);
                    }
                },
                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        "targets": [ -1 ], //last column
                        "orderable": false,//set not orderable
                        "className": "dt-center",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            var $btn_add = $("<button>", { class:"btn btn-primary btn-xs add-symptomp-btn","type": "button",
                                "data-value": cellData}).css("width","100%");
                            $btn_add.append("<span class='glyphicon glyphicon-plus'></span>&nbsp Add");

                            $(td).html($btn_add);
                        }
                    },
                    {
                        "targets": [0], //last column
                        "orderable": false//set not orderable}
                    }
                ]

            });

        });

        $('#lookup-symptomp-modal').on('hidden.bs.modal', function () {
            //$('#dataTables-symptomp').html("");
            $('#dataTables-symptomp').dataTable().fnDestroy();
        })
    });

</script>