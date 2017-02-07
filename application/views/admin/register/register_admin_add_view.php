<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Register
        <small>Admin</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Register</a></li>
        <li class="active">Admin</li>
    </ol>
</section>

<section class="content">
    <div class="box" id="content-container" >
        <div class="box-header">
            <h3 class="box-title"></h3>
        </div>

        <div class="box-body">
            <p>
            <div class="row">
                <div class="col-lg-8">
                    <button type="button" class="btn btn-primary btn-xl" id="btn-save">
                        <span class="glyphicon glyphicon-plus"></span>&nbsp SAVE
                    </button>
                </div>
            </div>
            </p>
            <form id="patient-form-edit" action="">
                <input type="hidden" class="form-control" id="patient-id-edit">
                <div class="form-group">
                    <label for="username-input" class="control-label">Username :</label>
                    <span class="cd-error-message label label-danger" id="err-username-input"></span>
                    <input type="text" class="form-control" id="username-input" placeholder="Username" data-label="#err-username-input" autofocus>
                </div>
                <div class="form-group">
                    <label for="password-input" class="control-label">Password :</label>
                    <span class="cd-error-message label label-danger" id="err-password-input"></span>
                    <input type="password" class="form-control" id="password-input" placeholder="Password" data-label="#err-password-input">
                </div>
                <div class="form-group">
                    <label for="confirm-password-input" class="control-label">Confirm Password :</label>
                    <span class="cd-error-message label label-danger" id="err-confirm-password-input"></span>
                    <input type="password" class="form-control" id="confirm-password-input" placeholder="Password" data-label="#err-confirm-password-input">
                </div>
                <div class="form-group">
                    <label for="email-input" class="control-label">Email :</label>
                    <span class="cd-error-message label label-danger" id="err-email-input"></span>
                    <input type="text" class="form-control" id="email-input" placeholder="Email" data-label="#err-email-input" autofocus>
                </div>
            </form>

        </div>
    </div>
</section>
<script>

    $(document).ready( function($) {
        function validate() {
            var err = 0;

            if (!$('#username-input').validateRequired()) {
                err++;
            }

            if (!$('#password-input').validateRequired()) {
                err++;
            }

            if(!$('#confirm-password-input').validateRequired()) {
                err++;
            }else{
                var pass = $('#password-input').val();
                var con_pass = $('#confirm-password-input').val();

                if(pass != con_pass){
                    $("#err-confirm-password-input").html("Konfirmasi Password tidak sesuai dengan Password !");
                    err++;
                }
            }

            if(!$('#email-input').validateEmailForm()){
                err++;
            }

            if (err != 0) {
                return false;
            } else {
                return true;
            }
        }

        // SAVE DATA TO DB
        $('#btn-save').click(function(){
            if (validate()) {
                var formData = new FormData();
                formData.append("password", $("#password-input").val());
                formData.append("username", $("#username-input").val());
                formData.append("email", $("#email-input").val());

                $(this).saveData({
                    url: "<?php echo site_url('RegisterAdmin/createAdmin')?>",
                    data: formData,
                    locationHref: "<?php echo site_url('RegisterAdmin/goToAddAdminForm')?>",
                    hrefDuration : 1000
                });

            }
            e.preventDefault();
        });
    });

</script>