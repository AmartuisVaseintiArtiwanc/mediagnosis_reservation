<!--Modal ADD-->
<div class="modal fade" id="disease-modal-add" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal-title-add">Add New Disease</h4>
            </div><!--modal header-->

            <div class="modal-body">
                <div class="alert alert-danger hidden" id="err-msg">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                </div>
                <form id="disease-form-add" action="">
                    <div class="form-group">
                        <label for="master-name-add" class="control-label cd-name">Disease Name :</label>
                        <span class="cd-error-message label label-danger" id="err-master-name-add"></span>
                        <input type="text" class="form-control" id="master-name-add" name="kategori_name"
                               placeholder="Name" data-label="#err-master-name-add" autofocus>
                    </div>
                </form>
            </div><!--modal body-->

            <div class="modal-footer">                
                <button type="submit" class="btn btn-primary" id="btn-save">Save</button>
            </div><!--modal footer-->

        </div><!--modal content-->
    </div><!--modal dialog-->
</div>

<!--Modal EDIT-->
<div class="modal fade" id="disease-modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal-title-edit"></h4>
            </div><!--modal header-->

            <div class="modal-body">
                <div class="alert alert-danger hidden" id="err-msg">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                </div>
                <form id="disease-form-edit" action="">
                    <input type="hidden" class="form-control" id="master-id">
                    <div class="form-group">
                        <label for="master-name-edit" class="control-label cd-name">Disease Name :</label>
                        <span class="cd-error-message label label-danger" id="err-master-name-edit"></span>
                        <input type="text" class="form-control" id="master-name-edit"
                               placeholder="Name" data-label="#err-master-name-edit">
                    </div>
                    <div class="form-group">
                        <label for="master-desc-edit" class="control-label cd-name">Description :</label>
                        <span class="cd-error-message label label-danger" id="err-master-desc-edit"></span>
                        <textarea class="form-control" id="master-desc-edit"
                               placeholder="Disease Description" data-label="#err-master-desc-edit" rows="10" ></textarea>
                    </div>
                    <div class="form-group">
                        <label for="master-caused-edit" class="control-label cd-name">Caused :</label>
                        <span class="cd-error-message label label-danger" id="err-master-caused-edit"></span>
                        <textarea class="form-control" id="master-caused-edit"
                                  placeholder="Caused" data-label="#err-master-caused-edit" rows="10" ></textarea>
                    </div>
                    <div class="form-group">
                        <label for="master-treatment-edit" class="control-label cd-name">Treatment :</label>
                        <span class="cd-error-message label label-danger" id="err-master-treatment-edit"></span>
                        <textarea class="form-control" id="master-treatment-edit"
                                  placeholder="Treatment" data-label="#err-master-treatment-edit" rows="10" ></textarea>
                    </div>
                </form>
            </div><!--modal body-->

            <div class="modal-footer">
                <p id="created"></p>
                <p id="last_modified"></p>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="btn-update">Edit</button>
            </div><!--modal footer-->

        </div><!--modal content-->
    </div><!--modal dialog-->
</div>

<script>

    $(document).ready( function($) {
        function validate() {
            var err = 0;

            if (!$('#master-name-add').validateRequired()) {
                err++;
            }

            if (err != 0) {
                return false;
            } else {
                return true;
            }
        }
        function validateEdit() {
            var err = 0;

            if (!$('#master-name-edit').validateRequired()) {
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
                formData.append("name", $("#master-name-add").val());

                $(this).saveData({
                    url: "<?php echo site_url('Disease/createDisease')?>",
                    data: formData,
                    locationHref: "<?php echo site_url('Disease')?>",
                    hrefDuration : 1000
                });
            }
            e.preventDefault();
        };

        var updateDataEvent = function(e){
            if (validateEdit()) {
                var formData = new FormData();
                formData.append("id", $("#master-id").val());
                formData.append("name", $("#master-name-edit").val());

                if($("#master-desc-edit").val() != ""){
                    formData.append("desc", $("#master-desc-edit").val());
                }
                if($("#master-caused-edit").val() != ""){
                    formData.append("caused", $("#master-caused-edit").val());
                }
                if($("#master-treatment-edit").val() != ""){
                    formData.append("treatment", $("#master-treatment-edit").val());
                }

                $(this).saveData({
                    url: "<?php echo site_url('Disease/editDisease')?>",
                    data: formData,
                    locationHref: "<?php echo site_url('Disease')?>",
                    hrefDuration : 1000
                });
            }
            e.preventDefault();
        };

        // SAVE DATA TO DB
        $('#btn-save').click(saveDataEvent);
        $("#Disease-form-add").on("submit", saveDataEvent);

        // UPDATE DATA TO DB
        $('#btn-update').click(updateDataEvent);
        $("#Disease-form-edit").on("submit", updateDataEvent);
    });

</script>