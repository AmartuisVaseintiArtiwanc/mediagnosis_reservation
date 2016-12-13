<!--Modal ADD-->
<div class="modal fade" id="patient-modal-add" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal-title-add">Add New Patient</h4>
            </div><!--modal header-->

            <div class="modal-body">
                <div class="alert alert-danger hidden" id="err-msg">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                </div>
                <form id="patient-form-add" action="">
                    <div class="form-group">
                        <label for="master-patient-name-add" class="control-label cd-patient-name-add">Patient Name :</label>
                        <span class="cd-error-message label label-danger" id="err-patient-name-add"></span>
                        <input type="text" class="form-control" id="patient-name-add" placeholder="Name" data-label="#err-patient-name-add" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="no-ktp-add" class="control-label cd-no-ktp-add">No. KTP :</label>
                        <span class="cd-error-message label label-danger" id="err-no-ktp-add"></span>
                        <input type="text" class="form-control" id="no-ktp-add" placeholder="No. KTP [16 Digit]" data-label="#err-no-ktp-add">
                    </div>
                    <div class="form-group">
                        <label for="no-bpjs-add" class="control-label cd-no-bpjs-add">No. BPJS :</label>
                        <span class="cd-error-message label label-danger" id="err-no-bpjs-add"></span>
                        <input type="text" class="form-control" id="no-bpjs-add" placeholder="No. BPJS [15 Digit]" data-label="#err-no-bpjs-add">
                    </div>
                    <div class="form-group">
                      <label for="gender-add" class="control-label cd-gender-add">Gender :</label>
                      <span class="cd-error-message label label-danger" id="err-gender-add"></span>
                      <select class="form-control" id="gender-add" data-label="#err-gender-add">
                        <option value="Laki-laki">Laki-Laki</option>
                        <option value="Perempuan">Perempuan</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="participant-status-add" class="control-label cd-participant-status-add">Participant Status :</label>
                      <span class="cd-error-message label label-danger" id="err-participant-status-add"></span>
                      <select class="form-control" id="participant-status-add" data-label="#err-participant-status-add">
                        <option value="AKTIF">AKTIF</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="participant-type-add" class="control-label cd-participant-type-add">Participant Type :</label>
                      <span class="cd-error-message label label-danger" id="err-participant-type-add"></span>
                      <select class="form-control" id="participant-type-add" data-label="#err-participant-type-add">
                        <option value="Karyawan Swasta">Karyawan Swasta</option>
                      </select>
                    </div>
                </form>
            </div><!--modal body-->

            <div class="modal-footer">                
                <button type="submit" class="btn btn-primary" id="btn-save">Save</button>
            </div><!--modal footer-->

        </div><!--modal content-->
    </div><!--modal dialog-->
</div>

<script>

    $(document).ready( function() {

        function validate() {
            var err = 0;

            if (!$('#patient-name-add').validateRequired()) {
                err++;
            }else if (!$('#no-ktp-add').validateRequired()) {
                err++;
            }else if (!$('#no-bpjs-add').validateRequired()) {
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
                formData.append("patientName", $("#patient-name-add").val());
                formData.append("ktpID", $("#no-ktp-add").val());
                formData.append("bpjsID", $("#no-bpjs-add").val());
                formData.append("gender", $("#gender-add").find(":selected").val());
                formData.append("participantStatus", $("#participant-status-add").find(":selected").val());
                formData.append("participantType", $("#participant-type-add").find(":selected").val());


                $(this).saveData({
                    url: "<?php echo site_url('Register/doInsertPatientOffline')?>",
                    data: formData,
                    locationHref: "<?php echo site_url('Register/registerOfflinePatient')?>",
                    hrefDuration : 1000
                });
            }
            e.preventDefault();
        };


        // SAVE DATA TO DB
        $('#btn-save').click(saveDataEvent);
        $("#Doctor-form-add").on("submit", saveDataEvent);
    });

</script>