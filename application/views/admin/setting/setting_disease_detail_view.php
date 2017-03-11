<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/bootstrap-editable/css/bootstrap-editable.css">

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
    .tr-edit td{
        color:#2196F3;
        font-weight: bold;
    }
    .tr-cancel td{
        color:#d2322d;
        font-weight: bold;
    }
    .tr-new td{
        color:#4CAF50;
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
 <?php $this->load->view('admin/lookup/lookup_symptomp')?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Setting
        <small>Gejala </small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Setting</a></li>
        <li class="active">Gejala </li>
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
            <h3 class="box-title">Setting Penyakit </h3>
        </div>
        <!-- form start -->
        <div class="box-body">
            <div class="well well-sm">
                <button type="button" class="btn btn-primary" id="btn-save">
                    <span class="glyphicon glyphicon-floppy-save"></span>&nbsp SIMPAN
                </button>
				 <a class="main-nav" href="#">
                    <button type="button" class="btn btn-success" id="lookup-symptomp-btn"
                            data-toggle="modal" data-target="#lookup-symptomp-modal">
                        <span class="glyphicon glyphicon-plus"></span>&nbsp Tambah Gejala
                    </button>
                </a>
                <a href="<?=site_url('SDisease/index')?>">
                    <button type="button" class="btn btn-default">
                        <span class="glyphicon glyphicon-circle-arrow-left"></span>&nbsp Kembali
                    </button>
                </a>
            </div>

            <div class="row"> 
				<div class="col-lg-12">
					<input type="hidden" id="header_id" value="<?php echo $data_setting_header->diseaseID;?>">
					<h1><?php echo $data_setting_header->diseaseName;?></h1>          
				</div>                               
            </div>

            <br/>
            <table class="table table-bordered table-striped table-hover" id="tbl-detail">
                <thead>
                <tr>
                    <th width="70%" style = "text-align:left;">Gejala</th>
                    <th width="20%" style = "text-align:left;">Bobot</th>             
                    <th width="10%" style = "text-align:center;">Option</th>
                </tr>
                </thead>

                <tbody id="detail-content">
                <?php foreach($data_setting_detail as $row){?>
                    <tr class="old-item" data-id="<?php echo $row['symptompID'];?>" data-weight="" data-edit="0" data-status="clear">
                        <td class="symptomp-name-td"><?php echo $row['symptompName'];?></td>
                        <td class="symptomp-weight-td""><a href="#" class="symptomp-weight" data-value="<?php echo $row['weight'];?>" data-old-value="<?php echo $row['weight'];?>"><?php echo $row['weight'];?></a></td>
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
<script src="<?php echo base_url();?>assets/custom/setting_disease.js" type="text/javascript"></script>

<script type="text/javascript">
    var $currentRow = "";
	var $data_symptomp_current = <?php echo json_encode($data_setting_detail);?>;
	
	$(".sidebar-menu").find(".active").removeClass("active");
	$(".mediagnosis-navigation-setting").addClass("active");
	
	var deletedSymptomp = [];
    $(function(){
        // Jquery draggable
        $('.modal-dialog').draggable({
            handle: ".modal-header"
        });

        //SAVE
        $("#btn-save" ).click(function(){
            if(validateSymptompInput()){
                $('#error-msg').addClass("hidden");
                var header_data_setting = new Object();
                header_data_setting.diseaseID = $("#header_id").val();
							
				// Deleted Symptomp
				var deleted_data_setting = [];
                // Edit Symptomp
                var edited_data_setting = [];

				$( "tr.old-item" ).each(function( index, element ) {					
					var symptomp = $(this).attr("data-id");
                    var $status =  $(this).attr("data-status");
                    var $isEdit =  $(this).attr("data-edit");

					if($status=="delete"){
						var detailData = {						
							symptompID : symptomp						
						};
						deleted_data_setting.push(detailData);	
					}

                    if($status=="clear" && $isEdit==1){
                        var $weight =  $(this).attr("data-weight");
                        var detailData = {
                            symptompID : symptomp,
                            weight : $weight
                        };
                        edited_data_setting.push(detailData);
                    }
				});
				
                var data_setting = new Array();
                data_setting.push(header_data_setting);
				data_setting.push(detailItemSymptomp);
				data_setting.push(deleted_data_setting);
                data_setting.push(edited_data_setting);

                var data_post = {
                    data :data_setting
                }
                //alert(JSON.stringify(data_setting));
                // ajax mulai disini
				
                $.ajax({
                    url: "<?php echo site_url('SDisease/saveDisease')?>",
                    data: data_post,
                    type: "POST",
                    dataType: 'json',
                    success: function(msg){
                        if(msg.status=='error'){
                            alertify.error(msg.msg);
                        }else{
                            alertify.success(msg.msg);
                            location.href = "<?= site_url("SDisease")?>";
                        }
                    },
                    error:function(msg){
                        alertify.error('Failed to response server!');
                    }
                });
				
            }
        });

        $('.del-old-item-btn').click(function() {
            var $tr = $(this).closest("tr");
			var $status = $tr.attr("data-status");
            var $isEdit = $tr.attr("data-edit");
			
			if($status == "delete"){
				$tr.removeClass('tr-cancel');
				$tr.attr("data-status","clear");
                if($isEdit==1){
                    $tr.addClass('tr-edit');
                }
			}else{
				$tr.addClass('tr-cancel');
				$tr.attr("data-status","delete");
				//alert($status);
			}           
        });

        $('.symptomp-weight').editable({
            type: 'text',
            display: function(value) {
                // set Total-discount
                var $tr = $(this).closest("tr");

                var $old_data = $(this).attr("data-old-value");
                if($old_data == value){
                    $tr.removeClass('tr-edit');
                    $tr.attr("data-edit","0");
                }else{
                    $tr.addClass("tr-edit");
                    $(this).attr("data-value",value);
                    $tr.attr("data-weight",value);
                    $tr.attr("data-edit","1");
                }
                $(this).text(value);
            },
            validate: function(value) {
                var regex = /^\d+(?:\.+\d*)?$/;
                if(! regex.test(value)) {
                    return 'numbers only!';
                }
            }
        });
    });
</script>