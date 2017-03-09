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
    input[type=checkbox]{
        height: 18px;
        width: 18px;
    }
</style>

<!--Lookup Master -->
<?php $this->load->view('lookup/lookup_poli')?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Setting
        <small>Schedule </small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Setting</a></li>
        <li class="active">Schedule </li>
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
            <h3 class="box-title">Setting Schedule </h3>
        </div>
        <!-- form start -->
        <div class="box-body">
            <div class="well well-sm">
                <button type="button" class="btn btn-primary" id="btn-save">
                    <span class="glyphicon glyphicon-floppy-save"></span>&nbsp SAVE
                </button>
                <a href="<?=site_url('SettingSchedule/index/'.$superUserID)?>">
                    <button type="button" class="btn btn-default">
                        <span class="glyphicon glyphicon-circle-arrow-left"></span>&nbsp Back to List
                    </button>
                </a>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <?php if(isset($data_setting_header)){?>
                        <input type="hidden" id="clinic-id" value="<?php echo $data_setting_header->clinicID;?>">
                        <input type="hidden" id="poli-id" value="<?php echo $data_setting_header->poliID;?>">
                        <h1><?php echo $data_setting_header->clinicName;?></h1>
                        <h1><?php echo $data_setting_header->poliName;?></h1>
                    <?php } ?>
                </div>
            </div>

            <br/>
            <table class="table table-bordered table-striped table-hover" id="tbl-detail">
                <thead>
                <tr>
                    <th width="20%" style = "text-align:left;">Day</th>
                    <th width="25%" style = "text-align:left;">Open Time</th>
                    <th width="25%" style = "text-align:left;">Close Time</th>
                    <th width="10%" style = "text-align:center;">Active</th>
                </tr>
                </thead>

                <tbody id="detail-content">
                <?php foreach($data_setting_detail as $row){?>
                    <tr class="old-item" data-id="<?php echo $row['scheduleID'];?>" data-status="clear">
                        <td class="schedule-day"><?php echo $row['scheduleDay'];?></td>
                        <td class="open-time-td">
                            <div class="bootstrap-timepicker">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control timepicker open-time"
                                               value="<?php echo $row['openTime']; ?>">
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                            </div>
                        </td>
                        <td class="close-time-td">
                            <div class="bootstrap-timepicker">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control timepicker close-time"
                                               value="<?php echo $row['closeTime'];?>">
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                            </div>
                        </td>
                        <td class="td-center is-open-td">
                            <input type="checkbox"
                                   <?php if($row['isOpen']==1) echo 'checked';?>
                                   value="true" class="is-open"/>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>

            </table>
        </div>
    </div>
</section><!-- div container -->

<script type="text/javascript">
    var $currentRow = "";
    var $data_poli_current = <?php echo json_encode($data_setting_detail);?>;

    var deletedPoli = [];
    $(function(){
        //Timepicker
        //Timepicker
        $(".timepicker").timepicker({
            showInputs: false,
            showMeridian:false
        });
        //$('.timepicker').timepicker('setTime', '13:45');

        //SAVE
        $("#btn-save" ).click(function(){
            $('#error-msg').addClass("hidden");
            var header_data_setting = new Object();
            header_data_setting.clinicID = $("#clinic-id").val();
            header_data_setting.poliID = $("#poli-id").val();

            // Detail Schedule
            var detail_schedule_setting = [];
            $( "tr.old-item" ).each(function( index, element ) {
                var schedule = $(this).attr("data-id");
                var openTime = $(this).find("input.open-time").val();
                var closeTime = $(this).find("input.close-time").val();
                var isOpen = $(this).find("input.is-open");

                var active = 0;
                if (isOpen.is(":checked")) {
                    active=1;
                }

                var detailData = {
                    scheduleID : schedule,
                    openTime : openTime,
                    closeTime : closeTime,
                    active : active
                };
                detail_schedule_setting.push(detailData);
            });

            var data_setting = new Array();
            data_setting.push(header_data_setting);
            data_setting.push(detail_schedule_setting);

            var data_post = {
                data :data_setting
            }
            //alert(JSON.stringify(data_setting));
            // ajax mulai disini

            $.ajax({
                url: "<?php echo site_url('SettingSchedule/saveSchedule')?>",
                data: data_post,
                type: "POST",
                dataType: 'json',
                success: function(msg){
                    if(msg.status=='error'){
                        alertify.error(msg.msg);
                    }else{
                        alertify.success(msg.msg);
                        location.href = "<?= site_url("SettingSchedule")?>";
                    }
                },
                error:function(msg){
                    alertify.error('Failed to response server!');
                }
            });

        });
    });
</script>