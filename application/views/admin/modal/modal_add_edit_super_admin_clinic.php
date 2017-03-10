<!--Modal ADD-->
<div class="modal fade" id="super-admin-clinic-modal-add" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal-title-add">Tambah Super Admin Klinik Baru</h4>
            </div><!--modal header-->

            <div class="modal-body">
                <div class="alert alert-danger hidden" id="err-msg">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                </div>
                <form id="super-admin-clinic-form-add" action="">
                                  
                    <div class="form-group">
                        <label for="master-username-add" class="control-label">Username :</label>
                        <span class="cd-error-message label label-danger" id="err-master-username-add"></span>
                        <input type="text" class="form-control" id="master-username-add" name="acc_username"
                               placeholder="Username Anda" data-label="#err-master-username-add" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="master-password-add" class="control-label">Password :</label>
                        <span class="cd-error-message label label-danger" id="err-master-password-add"></span>
                        <input type="password" class="form-control" id="master-password-add" name="acc_password"
                               placeholder="Password Anda" data-label="#err-master-password-add" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="master-confirm-password-add" class="control-label">Konfirmasi Password :</label>
                        <span class="cd-error-message label label-danger" id="err-master-confirm-password-add"></span>
                        <input type="password" class="form-control" id="master-confirm-password-add" name="acc_confirm_password"
                               placeholder="Ulangi Password Anda" data-label="#err-master-confirm-password-add" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="master-email-add" class="control-label">Email :</label>
                        <span class="cd-error-message label label-danger" id="err-master-email-add"></span>
                        <input type="text" class="form-control" id="master-email-add" name="acc_email"
                               placeholder="Email Anda" data-label="#err-master-email-add" autofocus>
                    </div>
                </form>
            </div><!--modal body-->

            <div class="modal-footer">                
                <button type="submit" class="btn btn-primary" id="btn-save">Save</button>
            </div><!--modal footer-->

        </div><!--modal content-->
    </div><!--modal dialog-->
</div>

<!--Modal EDIT Account-->
<div class="modal fade" id="super-admin-clinic-modal-edit-account" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Ubah Akun Super Admin Klinik</h4>
            </div><!--modal header-->

            <div class="modal-body">
                <div class="alert alert-danger hidden" id="err-msg">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                </div>
                <form id="super-admin-clinic-form-edit-account" action="">
                    <input type="hidden" class="form-control" id="master-user-id">
                    <div class="form-group">
                        <label for="master-username-edit" class="control-label">Ubah Username :</label>
                        <span class="cd-error-message label label-danger" id="err-master-username-edit"></span>
                        <input type="text" class="form-control" id="master-username-edit"
                               placeholder="Ubah Username" data-value="" data-label="#err-master-username-edit">
                    </div>
                    <div class="form-group">
                        <label for="master-email-add" class="control-label">Ubah Email :</label>
                        <span class="cd-error-message label label-danger" id="err-master-email-edit"></span>
                        <input type="text" class="form-control" id="master-email-edit" name="acc_email"
                               placeholder="Email Anda" data-value="" data-label="#err-master-email-edit" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="master-password-edit" class="control-label">Reset Password :</label>
                        <span class="cd-error-message label label-danger" id="err-master-password-edit"></span>
                        <input type="password" class="form-control" id="master-password-edit"
                               placeholder="Reset Password" data-label="#err-master-password-edit">
                    </div>
                    <div class="form-group">
                        <label for="master-confirm-password-edit" class="control-label">Konfirmasi Reset Password :</label>
                        <span class="cd-error-message label label-danger" id="err-master-confirm-password-edit"></span>
                        <input type="password" class="form-control" id="master-confirm-password-edit"
                               placeholder="Konfirmasi Reset Password" data-label="#err-master-confirm-password-edit">
                    </div>
					
					<div class="form-group">
                        <label for="master-isactive-edit" class="control-label">Status :</label>
                        <input type="hidden" class="form-control" id="master-isactive-edit">
                        <br/>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-isactive" data-status="1" id="btn-status-active">ACTIVE</button>
                            <button type="button" class="btn btn-default btn-isactive" data-status="0" id="btn-status-no-active">NO ACTIVE</button>
                        </div>
                    </div>
                </form>
            </div><!--modal body-->

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="btn-update-account">Edit</button>
            </div><!--modal footer-->

        </div><!--modal content-->
    </div><!--modal dialog-->
</div>

<script>

    $(document).ready( function($) {
       
        $(".btn-isactive").click(function(){
            var $status = $(this).attr("data-status");
            if($status == 1){
                $("#btn-status-active").removeClass("btn-default").addClass("btn-success");
                $("#btn-status-no-active").removeClass("btn-danger").addClass("btn-default");
                $("#master-isactive-edit").val(1);
            }else if($status==0){
                $("#btn-status-active").removeClass("btn-success").addClass("btn-default");
                $("#btn-status-no-active").removeClass("btn-default").addClass("btn-danger");
                $("#master-isactive-edit").val(0);
            }
        });

        function validate() {
            var err = 0;          

            if (!$('#master-username-add').validateRequired()) {
                err++;
            }

            if (!$('#master-password-add').validateRequired()) {
                err++;
            }

            if(!$('#master-confirm-password-add').validateRequired()) {
                err++;
            }

            if(!$('#master-confirm-password-add').validateConfirmPassword({
                    compareValue : $('#master-password-add').val()}) ) {
                err++;
            }

            if (!$('#master-email-add').validateEmailForm()) {
                err++;
            }

            if (err != 0) {
                return false;
            } else {
                return true;
            }
        }

        function validateEditAccount() {
            var err = 0;

            if (!$('#master-username-edit').validateRequired()) {
                err++;
            }

            if(!$('#master-confirm-password-edit').validateConfirmPassword({
                    compareValue : $('#master-password-edit').val()}) ) {
                err++;
            }

            if (!$('#master-email-edit').validateEmailForm()) {
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
                formData.append("username", $("#master-username-add").val());
                formData.append("password", $("#master-password-add").val());
                formData.append("email", $("#master-email-add").val());
                
                $(this).saveData({
                    url: "<?php echo site_url('SuperAdminClinic/createClinic')?>",
                    data: formData,
                    locationHref: "<?php echo site_url('SuperAdminClinic/index')?>",
                    hrefDuration : 1000
                });
            }
            e.preventDefault();
        };     

        var updateAccountDataEvent = function(e){
            if (validateEditAccount()) {
                var formData = new FormData();

                var $username =  $("#master-username-edit").val();
                var $email =  $("#master-email-edit").val();
                var $username_old =  $("#master-username-edit").attr("data-value");
                var $email_old =  $("#master-email-edit").attr("data-value");
                var $password =  $("#master-password-edit").val();
				var $is_active =  $("#master-isactive-edit").val();

                if($username != $username_old){
                    formData.append("username",$username);
                }
                if($email != $email_old){
                    formData.append("email",$email);
                }
                if($password != ""){
                    formData.append("password", $password);
                }
                formData.append("isActive", $is_active);   
				formData.append("id", $("#master-user-id").val());               

                $(this).saveData({
                    url: "<?php echo site_url('SuperAdminClinic/editAccountSuperAdminClinic');?>",
                    data: formData,
                    locationHref: "<?php echo site_url('SuperAdminClinic/index');?>",
                    hrefDuration : 1000
                });
            }
            e.preventDefault();
        };

        // SAVE DATA TO DB
        $('#btn-save').click(saveDataEvent);
        $("#super-admin-clinic-form-add").on("submit", saveDataEvent);

        // UPDATE ACCOUNT TO DB
        $('#btn-update-account').click(updateAccountDataEvent);
        $("#super-admin-form-edit-account").on("submit", updateAccountDataEvent);
    });

</script>