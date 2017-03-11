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
        <small>Klinik</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Transaction</a></li>
        <li class="active">Rating</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="box" id="content-container" >
        <div class="box-header">
            <h3 class="box-title">Update Rating</h3>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <p>
                    <button type="button" class="btn btn-primary btn-xl" id="btn-update-clinic">
                        <span class="glyphicon glyphicon-open"></span>&nbsp Update Rating CLINIC
                    </button>
                    </p>
                </div>

            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p>
                    <button type="button" class="btn btn-primary btn-xl" id="btn-update-doctor"">
                    <span class="glyphicon glyphicon-open"></span>&nbsp Update Rating DOCTOR
                    </button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!--Modal ADD-->
<div class="modal fade" id="loading-modal" tabindex="-1" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>Loading</b></h4>
            </div><!--modal header-->

            <div class="modal-body">
                <div class="box">
                    <h3 class="text-center">Procesing Update</h3>
                    <br/>
                    <div class="overlay loading-screen-queue">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>

            </div><!--modal body-->

            <div class="modal-footer">
            </div><!--modal footer-->

        </div><!--modal content-->
    </div><!--modal dialog-->
</div>

<script>
    $(function() {
        var $base_url = "<?php echo site_url();?>/";

        $('#btn-update-clinic').click(function () {
            $.ajax({
                url: $base_url+"Rating/doUpdateClinic",
                type: "POST",
                dataType: 'json',
                cache:false,
                beforeSend:function(){
                    //SHOW LOADING SCREEN
                    $("#loading-modal").modal("show");
                },
                success:function(data){
                    if(data.status != "error"){
                        alertify.success(data.msg);
                        $("#loading-modal").modal("hide");
                    }else{
                        alertify.error("Failed to Update Rating !");
                        $("#loading-modal").modal("hide");
                    }

                },
                error: function(xhr, status, error) {
                    //var err = eval("(" + xhr.responseText + ")");
                    //alertify.error(xhr.responseText);
                    alertify.error("Cannot response server !");
                }
            });
        });

        $('#btn-update-doctor').click(function () {

            alertify.confirm("Apakah Anda yakin untuk Meng-Update Rating Klinik ?",function(){
                $.ajax({
                    url: $base_url+"Rating/doUpdateDoctor",
                    type: "POST",
                    dataType: 'json',
                    cache:false,
                    beforeSend:function(){
                        //SHOW LOADING SCREEN
                        $("#loading-modal").modal("show");
                    },
                    success:function(data){
                        if(data.status != "error"){
                            alertify.success(data.msg);
                            $("#loading-modal").modal("hide");
                        }else{
                            alertify.error("Failed to Update Rating !");
                            $("#loading-modal").modal("hide");
                        }
                    },
                    error: function(xhr, status, error) {
                        //var err = eval("(" + xhr.responseText + ")");
                        //alertify.error(xhr.responseText);
                        alertify.error("Cannot response server !");
                    }
                });
            }).setHeader("Konfirmasi Update");
        })
    });
</script>