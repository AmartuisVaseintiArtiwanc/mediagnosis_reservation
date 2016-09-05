<style>
    .small-box>.inner {
        padding: 20px;
    }
    .small-box .icon {
        top:20px;
        right: 20px;
    }
    .small-box h3{
        font-size: 58px;
    }
    .small-box p{
        font-size: 25px;
    }
</style>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Reservation
        <small>Clinic</small>

    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Reservation</a></li>
        <li class="active">Clinic</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
                <i class="fa fa-hospital-o"></i> <?php echo $data_clinic->clinicName;?>
                <?php if($this->session->userdata('role')=="super_admin"){?>
                    <a href="<?=site_url('Reservation/index')?>">
                        <button class="btn btn-primary pull-right" type="button">
                            <span class="glyphicon glyphicon-circle-arrow-left"></span> Back to list
                        </button>
                    </a>
                <?php } ?>
            </h2>
        </div>
        <!-- /.col -->
    </div>
    <div class="row">

        <?php foreach($reversation_clinic_data as $row) { ?>
        <div class="col-lg-4 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><?php echo $row['currentQueue'];?></h3>
                    <p><?php echo $row['poliName'];?></p>
                    <p>Total : <?php echo $row['totalQueue'];?></p>
                </div>
                <div class="icon">
                    <i class="ion ion-person"></i>
                </div>
                <a href="#" class="small-box-footer">
                    List Reservation <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <?php } ?>
    </div>
</section>
