<style>
    #lookup-admin-btn{
        cursor: pointer;
    }
    th.dt-center, td.dt-center { text-align: center; }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Register
        <small>Admin Klinik</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Register</a></li>
        <li class="active">Admin Klinik</li>
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
                <div class="form-group">
                    <label for="admin-input" class="control-label">Choose Admin :</label>
                    <span class="cd-error-message label label-danger" id="err-admin-input"></span>
                    <div class="input-group">
                        <input type="text" class="form-control" id="admin-input" placeholder="Admin" data-label="#err-admin-input" disabled>
                        <div class="input-group-addon" id="lookup-admin-btn">
                            <i class="fa fa-fw fa-search"></i>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="clinic-name-input" class="control-label">Clinic Name :</label>
                    <span class="cd-error-message label label-danger" id="err-clinic-name-input"></span>
                    <input type="text" class="form-control" id="clinic-name-input" placeholder="Clinic" data-label="#err-clinic-name-input" autofocus>
                </div>

            </form>

        </div>
    </div>
</section>
<!--Lookup Master -->
<?php $this->load->view('admin/lookup/lookup_admin')?>

<script>

    $(document).ready( function() {
        var $base_url = "<?php echo site_url();?>/";
        var selected = [];
        var table = "";

        function getData(){
            // Quickly and simply clear a table
            $('#dataTables-admin').dataTable().fnClearTable();
            // Restore the table to it's original state in the DOM by removing all of DataTables enhancements, alterations to the DOM structure of the table and event listeners
            $('#dataTables-admin').dataTable().fnDestroy();

            $.ajax({
                url: $base_url+"RegisterAdmin/getLookupAdminList",
                type: "POST",
                dataType: 'json',
                cache:false,
                success:function(data){
                    renderAdminData(data);
                    $("#lookup-admin-modal").modal("show");
                    $(".overlay").hide();
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
            $('#dataTables-admin').DataTable( {
                "aaData": $data.data,
                "lengthChange": false,
                "autoWidth": false,
                "aoColumns": [
                    { "mDataProp": "userName"},
                    { "mDataProp": "userID"}
                ],
                "columnDefs": [
                    {
                        "width": "80%",
                        "targets": 0
                    },
                    {
                        "width": "20%",
                        "targets": [ -1 ], //last column
                        "orderable": false,//set not orderable
                        "className": "dt-center",
                        "createdCell": function (td, cellData, rowData, row, col) {
                            var $btn_add = $("<button>", { class:"btn btn-primary btn-xs add-admin-btn","type": "button",
                                "data-value": cellData, "data-name": rowData.userName });
                            $btn_add.append("<span class='glyphicon glyphicon-plus'></span>&nbsp PILIH");
                            $(td).html($btn_add);
                        }
                    },
                ]
            } );
        }

        $("#lookup-admin-btn").click(function(){
            getData();
        });

        $('#dataTables-admin tbody').on('click', 'button.add-admin-btn', function () {
            var $id =  $(this).attr("data-value");
            var $name =  $(this).attr("data-name");
            // SET DATA
            $("#admin-input").val($name);
            $("#admin-input").attr("data-value",$id);

            // Close Lookup
            $("#lookup-admin-modal").modal("hide");
        });

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
            if (!$('#admin-input').validateRequired()) {
                err++;
            }
            if (!$('#clinic-name-input').validateRequired()) {
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
                formData.append("superUser", $("#admin-input").attr("data-value"));
                formData.append("clinic", $("#clinic-name-input").val());

                $(this).saveData({
                    url: "<?php echo site_url('RegisterAdmin/createClinic')?>",
                    data: formData,
                    locationHref: "<?php echo site_url('RegisterAdmin/goToAddClinicForm')?>",
                    hrefDuration : 1000
                });

            }
            e.preventDefault();
        });

    });

</script>
