<style>
    .table-hover tbody tr:hover{
        background-color: #C3F0ED;
    }
    .td-right{
        text-align: right;
    }
    .td-center{
        text-align: center;
    }
    .float-right{
        float: right;
    }
    .tr-cancel td{
        color:#d2322d;
        font-weight: bold;
    }
    .required{
        color: #dd4b39 !important;
    }
    #container-result{
        margin-top: 10px;
    }
    #container-result span.form-control{
        padding-bottom: 38px;
    }
    #discount, #total-result{
        font-size: 20px;
        font-weight: bold;
    }
</style>

 <!--Lookup Master -->
 <?php $this->load->view('admin/lookup/lookup_doctor')?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Setting
        <small>Poli </small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Setting</a></li>
        <li class="active">Poli </li>
    </ol>
</section>

<!--Error Container-->
<div class="error-container hidden" id="error-msg">
    <div class="btn-closed">
        <button type="button" class="btn btn-default btn-lg">
            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
        </button>
    </div>

</div>

<section class="content">

    <div class="box" id="content-container" >
        <div class="box-header">
            <h3 class="box-title">Setting Poli </h3>
        </div>
        <!-- form start -->
        <div class="box-body">
            <div class="well well-sm">
                <button type="button" class="btn btn-primary" id="btn-save">
                    <span class="glyphicon glyphicon-floppy-save"></span>&nbsp SAVE
                </button>
				 <a class="main-nav" href="#">
                    <button type="button" class="btn btn-success" id="lookup-doctor-btn"
                            data-toggle="modal" data-target="#lookup-doctor-modal">
                        <span class="glyphicon glyphicon-plus"></span>&nbsp Tambah Dokter
                    </button>
                </a>
                <a href="<?=site_url('SPoli/index/'.$superUserID)?>">
                    <button type="button" class="btn btn-default">
                        <span class="glyphicon glyphicon-circle-arrow-left"></span>&nbsp Kembali
                    </button>
                </a>
            </div>

            <div class="row"> 
				<div class="col-lg-12">
					<input type="hidden" id="header_id" value="<?php echo $data_setting_header->sClinicID;?>">
					<h1><?php echo $data_setting_header->poliName;?></h1>
				</div>                               
            </div>

            <br/>
            <table class="table table-bordered table-striped table-hover" id="tbl-detail">
                <thead>
                <tr>
                    <th width="70%" style = "text-align:left;">Dokter</th>
                    <th width="10%" style = "text-align:center;">Option</th>
                </tr>
                </thead>

                <tbody id="detail-content">
                <?php foreach($data_setting_detail as $row){?>
                    <tr class="old-item" data-id="<?php echo $row['doctorID'];?>" data-status="clear">
                        <td class="doctor-name"><?php echo $row['doctorName'];?></td>
                        <td class="td-center">
                            <button class="btn btn-danger del-old-item-btn" type="button">
                                <span class="glyphicon glyphicon-ban-circle"></span>
                            </button>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>

            </table>
        </div>
    </div>
</section><!-- div container -->

<script src="<?php echo base_url();?>assets/plugins/jquery.maskMoney.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/custom/setting_poli.js" type="text/javascript"></script>

<script type="text/javascript">
    var $currentRow = "";
	var $data_doctor_current = <?php echo json_encode($data_setting_detail);?>;
    var $super_user = <?php echo $superUserID;?>;
	
	$(".sidebar-menu").find(".active").removeClass("active");
	$(".mediagnosis-navigation-setting").addClass("active");
	
	var deletedDoctor = [];
    $(function(){
        // Jquery draggable
        $('.modal-dialog').draggable({
            handle: ".modal-header"
        });

        //SAVE
        $("#btn-save" ).click(function(){
            if(validateDoctorInput()){
                $('#error-msg').addClass("hidden");
                var header_data_setting = new Object();
                header_data_setting.sClinicID = $("#header_id").val();
                header_data_setting.superUserID = $super_user;
							
				// Deleted Doctor
				var deleted_data_setting = [];
				$( "tr.old-item" ).each(function( index, element ) {					
					var doctor = $(this).attr("data-id");
					var $status =  $(this).attr("data-status");
					if($status == "delete"){
						var detailData = {						
							doctorID : doctor
						};
						deleted_data_setting.push(detailData);	
					}									 
				});
				
                var data_setting = new Array();
                data_setting.push(header_data_setting);
				data_setting.push(detailItemDoctor);
				data_setting.push(deleted_data_setting);

                var data_post = {
                    data :data_setting
                }
                //alert(JSON.stringify(data_setting));
                // ajax mulai disini

                $.ajax({
                    url: "<?php echo site_url('SPoli/savePoli')?>",
                    data: data_post,
                    type: "POST",
                    dataType: 'json',
                    success: function(msg){
                        if(msg.status=='error'){
                            alertify.error(msg.msg);
                        }else{
                            alertify.success(msg.msg);
                            location.href = "<?= site_url("SPoli/index/".$superUserID)?>";
                        }
                    },
                    error:function(msg){
                        alertify.error('Terjadi kesalahan server!');
                    }
                });
            }
        });

        $('.del-old-item-btn').click(function() {
            var $tr = $(this).closest("tr");
			var $status = $tr.attr("data-status");
			
			if($status == "delete"){
				$tr.removeClass('tr-cancel');
				$tr.attr("data-status","clear");
				//alert($status);
			}else{
				$tr.addClass('tr-cancel');
				$tr.attr("data-status","delete");
				//alert($status);
			}           
        });
    });
</script>