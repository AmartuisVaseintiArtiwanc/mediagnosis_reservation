<?php $this->load->helper('HTML');
?>
<!--Sweet Alert-->
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.css">
<!--Sweet Alert-->
<script src="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<style>
    .cd-error-message{
        font-size:12px;
        visibility: visible;
    }
    .lookup-btn:hover{
        background-color: #3c8dbc;
        color: #fff;
        cursor: pointer;
    }
    .hidden{
        display: none;
    }
    table.dataTable thead > tr > th{
        padding-right: 8px!important;
    }
    th.dt-center, td.dt-center { text-align: center; }
    .alert-header{
        margin: 0px;
    }
</style>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Update Profile
        <small>Update Profile</small>
    </h1>
    <ol class="breadcrumb">
        <li class="active">Update Profile</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="" id="content-container" >
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Detil Akun</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" id="form-edit-account">
                            <div class="box-body">
                                <input type="hidden" class="form-control" value="<?php echo $account->userID;?>" id="master-user-id">
                                <div class="form-group">
                                    <label for="master-username-edit" class="control-label">Ubah Username :</label>
                                    <span class="cd-error-message label label-danger" id="err-master-username-edit"></span>
                                    <input type="text" class="form-control" id="master-username-edit"
                                           value="<?php echo $account->userName;?>"
                                           placeholder="Ubah Username" data-value="" data-label="#err-master-username-edit">
                                </div>
                                <div class="form-group">
                                    <label for="master-email-add" class="control-label">Ubah Email :</label>
                                    <span class="cd-error-message label label-danger" id="err-master-email-edit"></span>
                                    <input type="text" class="form-control" id="master-email-edit" name="acc_email"
                                           value="<?php echo $account->email;?>"
                                           placeholder="Email Anda" data-value="" data-label="#err-master-email-edit" autofocus>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" id="btn-change-password" data-value="0">Ubah Password</button>
                                </div>
                                <div class="form-group change-password-group">
                                    <label for="master-password-edit" class="control-label">Reset Password :</label>
                                    <span class="cd-error-message label label-danger" id="err-master-password-edit"></span>
                                    <input type="password" class="form-control" id="master-password-edit"
                                           placeholder="Reset Password" data-label="#err-master-password-edit">
                                </div>
                                <div class="form-group change-password-group">
                                    <label for="master-confirm-password-edit" class="control-label">Konfirmasi Reset Password :</label>
                                    <span class="cd-error-message label label-danger" id="err-master-confirm-password-edit"></span>
                                    <input type="password" class="form-control" id="master-confirm-password-edit"
                                           placeholder="Konfirmasi Reset Password" data-label="#err-master-confirm-password-edit">
                                </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                                <button type="button" class="btn btn-success btn-xl" id="btn-update-account">
                                    <span class="glyphicon glyphicon-floppy-save"></span>&nbsp SIMPAN
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Data Klinik</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" id="form-edit-clinic">
                            <div class="box-body">
                                <input type="hidden" class="form-control" value="<?php echo $clinic->clinicID;?>" id="master-clinic-id">
                                <div class="form-group">
                                    <label for="master-name-edit" class="control-label cd-name">Nama Klinik :</label>
                                    <span class="cd-error-message label label-danger" id="err-master-name-edit"></span>
                                    <input type="text" class="form-control" id="master-name-edit"
                                           value="<?php echo $clinic->clinicName;?>"
                                           placeholder="Name" data-label="#err-master-name-edit">
                                </div>
                                <div class="form-group">
                                    <label for="master-search-address-edit" class="control-label">Cari Alamat :</label>
                                    <span class="cd-error-message label label-danger" id="err-master-address-edit"></span>
                                    <input type="text" class="form-control" id="master-search-address-edit" placeholder="Alamat Klinik Anda"
                                           data-label="#err-master-address-edit" />
                                <textarea type="text" class="form-control" id="master-address-edit" placeholder="Alamat Klinik Anda"
                                          value="<?php echo $clinic->clinicAddress;?>" data-lat="<?php echo $clinic->latitude;?>"
                                          data-lng="<?php echo $clinic->longitude;?>"
                                          data-label="#err-master-address-edit" disabled><?php echo $clinic->clinicAddress;?></textarea>
                                </div>
                                <div class="map_canvas" id="map-canvas-edit"></div>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                                <button type="button" class="btn btn-success btn-xl" id="btn-update-clinic">
                                    <span class="glyphicon glyphicon-floppy-save"></span>&nbsp SIMPAN
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<script src="//maps.googleapis.com/maps/api/js?libraries=places&amp;key=AIzaSyDmzn_NKO9LsuSuhg4McW8oj_zXJk9h6Ig"></script>
<script src="<?php echo base_url();?>assets/plugins/geocomplete/jquery.geocomplete.js"></script>
<script>
    $(function() {
        var $base_url = "<?php echo site_url();?>/";
        $(".change-password-group").hide();

        $("#master-search-address-edit").geocomplete({
            map: "#map-canvas-edit"
        }).bind("geocode:result", function(event, result){
            var lat = result.geometry.location.lat();
            var lng = result.geometry.location.lng();
            var address = result.formatted_address;

            $("#master-address-edit").val(address);
            $("#master-address-edit").attr("data-lng", lng);
            $("#master-address-edit").attr("data-lat", lat);
            console.log(result.geometry.location.lat());
        });

        $("#btn-change-password").click(function(){
            var $value = $(this).attr("data-value");
            if($value == 1){
                $(this).attr("data-value",0);
            }else{
                $(this).attr("data-value",1);
            }
            $(".change-password-group").toggle();
        });

        function validateEditAccount() {
            var err = 0;
            var change_pass_flag = $("#btn-change-password").attr("data-value");

            if (!$('#master-username-edit').validateUsername()) {
                err++;
            }

            if(change_pass_flag == 1){
                if (!$('#master-password-edit').validateLengthRange({minLength:6,maxLength:50})) {
                    err++;
                }

                if(!$('#master-confirm-password-edit').validateRequired()) {
                    err++;
                }

                if(!$('#master-confirm-password-edit').validateConfirmPassword({
                        compareValue : $('#master-password-edit').val()}) ) {
                    err++;
                }
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

        function validateEdit() {
            var err = 0;

            if (!$('#master-name-edit').validateRequired()) {
                err++;
            }
            if (!$('#master-address-edit').validateRequired()) {
                err++;
            }

            if (err != 0) {
                return false;
            } else {
                return true;
            }
        }

        var updateAccountDataEvent = function(e){
            if (validateEditAccount()) {
                var formData = new FormData();

                var $username =  $("#master-username-edit").val();
                var $email =  $("#master-email-edit").val();
                var $username_old =  $("#master-username-edit").attr("data-value");
                var $email_old =  $("#master-email-edit").attr("data-value");
                var $password =  $("#master-password-edit").val();

                if($username != $username_old){
                    formData.append("username",$username);
                }
                if($email != $email_old){
                    formData.append("email",$email);
                }
                if($password != ""){
                    formData.append("password", $password);
                }
                formData.append("id", $("#master-user-id").val());

                $(this).saveData({
                    url: "<?php echo site_url('Profile/editAccount')?>",
                    data: formData,
                    locationHref: "<?php echo site_url('Login/logout')?>",
                    hrefDuration : 1000
                });
            }
            e.preventDefault();
        };

        var updateDataEvent = function(e){
            if (validateEdit()) {
                var formData = new FormData();
                formData.append("id", $("#master-clinic-id").val());
                formData.append("name", $("#master-name-edit").val());
                formData.append("address", $("#master-address-edit").val());
                formData.append("lat", $("#master-address-edit").attr("data-lat"));
                formData.append("lng", $("#master-address-edit").attr("data-lng"));

                $(this).saveData({
                    url: "<?php echo site_url('Profile/editClinic')?>",
                    data: formData,
                    locationHref: "<?php echo site_url('Profile/updateProfile')?>",
                    hrefDuration : 1000
                });
            }
            e.preventDefault();
        };

        // UPDATE ACCOUNT TO DB
        $('#btn-update-account').click(updateAccountDataEvent);
        $("#form-edit-account").on("submit", updateAccountDataEvent);

        // UPDATE DATA CLINIC TO DB
        $('#btn-update-clinic').click(updateDataEvent);
        $("#form-edit-clinic").on("submit", updateDataEvent);
    });
</script>