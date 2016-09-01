<!--Modal ADD-->
<div class="modal fade" id="cancel-penjualan-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal-title-cancel"></h4>
            </div><!--modal header-->

            <div class="modal-body">
                <div class="alert alert-danger hidden" id="err-msg">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                </div>
                <form id="cancel-penjualan-form" action="">
                    <input type="hidden" id="item-detail-id">
                    <div class="form-group">
                        <label for="master-name-add" class="control-label cd-name">Status :</label>
                        <select class="form-control" id="item-status-modal">
                            <option value="CLEAR">CLEAR</option>
                            <option value="CANCEL">CANCEL</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="err-alasan" class="control-label cd-name">Alasan :</label>
                        <span class="cd-error-message label label-danger" id="err-alasan"></span>
                        <textarea class="form-control" rows="3" id="alasan-txt" data-label="#err-alasan"></textarea>
                    </div>
                </form>
            </div><!--modal body-->

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="btn-save-modal">Submit</button>
            </div><!--modal footer-->

        </div><!--modal content-->
    </div><!--modal dialog-->
</div>

<script>

    $(document).ready( function($) {
        function validate() {
            var err = 0;

            if (!$('#alasan-txt').validateRequired()) {
                err++;
            }

            if (err != 0) {
                return false;
            } else {
                return true;
            }
        }

        var saveDataEvent = function(e) {
            if (validate()) {
                var formData = new FormData();
                formData.append("detailID", $("#item-detail-id").val());
                formData.append("alasan", $("#alasan-txt").val());
                formData.append("status", $("#item-status-modal").val());

                $.ajax({
                    url: "<?php echo site_url('Penjualan/cancelPenjualanDetailItem')?>",
                    data: formData,
                    type: "POST",
                    dataType: 'json',
                    cache:false,
                    contentType: false,
                    processData: false,
                    success:function(data){
                        if(data.status != 'error') {
                            if($("#item-status-modal").val() == 'CANCEL'){
                                $currentRow.addClass("tr-cancel");
                            }else{
                                $currentRow.removeClass("tr-cancel");
                            }

                            $('#cancel-penjualan-modal').modal("hide");
                            alertify.set('notifier','position', 'bottom-right');
                            alertify.success(data.msg);
                        }else{
                            $("#load_screen").hide();
                            alertify.set('notifier','position', 'bottom-right');
                            alertify.error(data.msg);
                        }
                    },
                    error: function(xhr, status, error) {
                        //var err = eval("(" + xhr.responseText + ")");
                        //alertify.error(xhr.responseText);
                        $("#load_screen").hide();
                        alertify.set('notifier','position', 'bottom-right');
                        alertify.error('Cannot response server !');
                    }
                });

            }
            e.preventDefault();
        };
        // SAVE DATA TO DB
        $('#btn-save-modal').click(saveDataEvent);
        $("#cancel-penjualan-form").on("submit", saveDataEvent);

    });

</script>