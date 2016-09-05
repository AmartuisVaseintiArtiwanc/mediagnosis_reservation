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
        Report Reservation
        <small>List Poli</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Reservation</a></li>
        <li class="active">List Clinic</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
                <i class="fa fa-hospital-o"></i> <?php echo $data_clinic->clinicName;?>
                <?php if($this->session->userdata('role')=="super_admin"){?>
                    <a href="<?=site_url('Reservation/goToReservationReportClinicList')?>">
                        <button class="btn btn-primary pull-right" type="button">
                            <span class="glyphicon glyphicon-circle-arrow-left"></span> Back to list
                        </button>
                    </a>
                <?php } ?>
            </h2>
        </div>
        <!-- /.col -->
    </div>
    <div class="box" id="content-container" >

        <div class="box-header">
            <h3 class="box-title">Poli List</h3>
        </div>

        <div class="box-body">
            <table  class="table table-bordered table-striped tbl-master" id="dataTables-list">
                <thead>
                <tr>
                    <th style = "text-align:left;">Poli</th>
                    <th style = "text-align:center;">Option</th>
                </tr>
                </thead>

                <tbody>
                    <?php
                        $i=0;
                        foreach($data_poli as $row){ ?>
                        <tr>
                            <td><?php echo $row['poliName'];?></td>
                            <td class="dt-center">
                                <a>
                                    <button class="btn btn-primary btn-xs edit-btn">
                                        <i class='fa fa-search'></i>&nbsp Detail
                                    </button>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</section>

<script>
    $(function() {
        $('#dataTables-list').DataTable({
            "lengthChange": false,
            "columnDefs": [
                {
                    "targets": [-1], //last column
                    "orderable": false//set not orderable
                }
            ]
        });
    });
</script>