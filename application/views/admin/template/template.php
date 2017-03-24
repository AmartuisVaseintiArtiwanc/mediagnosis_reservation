<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Mediagnosis Reservation</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/font-awesome/css/font-awesome.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/ionicon/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/dist/css/skins/_all-skins.min.css">
    <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/daterangepicker/daterangepicker-bs3.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <!-- Data Tables -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/datatables/dataTables.bootstrap.css">
  <!-- Alertify -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/alertify/alertify.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/alertify/themes/default.min.css">
  <!--Sweet Alert-->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.css">

  <!-- jQuery 2.2.0 -->
  <script src="<?php echo base_url();?>assets/plugins/jQuery/jQuery-2.2.0.min.js"></script>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>M</b>DG</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Mediagnosis</b>    </span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo base_url();?>assets/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $this->session->userdata('userName');?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo base_url();?>assets/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                    <?php echo $this->session->userdata('userName');?>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo site_url('Login/logout')?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo base_url();?>assets/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $this->session->userdata('userName');?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li class="active mediagnosis-navigation-dashboard"><a href="<?php echo site_url("Welcome");?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>

          <!--SUPER ADMIN  MASTER-->
          <?php if($this->session->userdata('role')=="mediagnosis_admin"){?>
            <li class="treeview mediagnosis-navigation-master">
              <a href="#">
                <i class="fa fa-database"></i>
                <span>Masters</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                  <!---->
                  <li><a href="<?php echo site_url("SuperAdminClinic");?>"><i class="fa fa-circle-o"></i> Super Admin Klinik</a></li>
                  <li><a href="<?php echo site_url("Clinic/indexAdmin");?>"><i class="fa fa-circle-o"></i> Klinik</a></li>
                  <li><a href="<?php echo site_url("Doctor/indexAdmin");?>"><i class="fa fa-circle-o"></i> Dokter</a></li>
                  <li><a href="<?php echo site_url("Poli");?>"><i class="fa fa-circle-o"></i> Poli</a></li>
                  <li><a href="<?php echo site_url("Disease");?>"><i class="fa fa-circle-o"></i> Penyakit</a></li>
                  <li><a href="<?php echo site_url("Symptomp");?>"><i class="fa fa-circle-o"></i> Gejala</a></li>
              </ul>
            </li>
          <?php } ?>

        <!--SETTING-->
        <?php if($this->session->userdata('role')=="mediagnosis_admin"){?>
            <li class="treeview mediagnosis-navigation-setting">
              <a href="#">
                <i class="fa fa-gear"></i>
                <span>Settings</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                  <li><a href="<?php echo site_url("SClinic/indexAdmin");?>"><i class="fa fa-circle-o"></i> Klinik</a></li>
                  <li><a href="<?php echo site_url("SPoli/indexAdmin");?>"><i class="fa fa-circle-o"></i> Poli</a></li>
                  <li><a href="<?php echo site_url("SettingSchedule/indexAdmin");?>"><i class="fa fa-circle-o"></i> Jadwal</a></li>
                  <li><a href="<?php echo site_url("SDisease");?>"><i class="fa fa-circle-o"></i> Penyakit</a></li>
              </ul>
            </li>

            <li class="treeview mediagnosis-navigation-register">
                <a href="#">
                    <i class="fa fa-sign-in"></i>
                    <span>Register Akun</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li><a href="<?php echo site_url("RegisterAdmin/goToAddAdminForm");?>"><i class="fa fa-circle-o"></i>Super Admin Klinik</a></li>
                  <li><a href="<?php echo site_url("RegisterAdmin/goToAddClinicForm");?>"><i class="fa fa-circle-o"></i> Admin Klinik</a></li>
                    <li><a href="<?php echo site_url("RegisterAdmin/goToAddDoctorForm");?>"><i class="fa fa-circle-o"></i> Dokter</a></li>
                </ul>
            </li>
            <li class="treeview mediagnosis-navigation-transaction">
                <a href="#">
                    <i class="fa fa-gear"></i>
                    <span>Transaksi</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo site_url("Rating/ratingClinicList");?>"><i class="fa fa-circle-o"></i> Update Rating Klinik</a></li>
					<li><a href="<?php echo site_url("Rating/ratingDoctorList");?>"><i class="fa fa-circle-o"></i> Update Rating Dokter</a></li>
					<li><a href="<?php echo site_url("Troubleshoot/reportedChat");?>"><i class="fa fa-circle-o"></i> Daftar Chat Bermasalah</a></li>
                </ul>
            </li>
            <li class="mediagnosis-navigation-diagnose"><a href="<?php echo site_url("Diagnose");?>"><i class="fa fa-stethoscope"></i> <span>Diagnosa</span></a></li>
        <?php } ?>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <?php
          if($main_content != "" || $main_content != null)
          $this->load->view($main_content);
      ?>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>CYBERITS</b>
    </div>
    <strong>Copyright &copy; 2016 <a href="">CYBERITS</a>.</strong> All rights
    reserved.
  </footer>

  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url();?>assets/plugins/jQueryUI/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url();?>assets/bootstrap/js/bootstrap.min.js"></script>

<!-- InputMask -->
<script src="<?php echo base_url();?>assets/plugins/input-mask/jquery.inputmask.js"></script>
<script src="<?php echo base_url();?>assets/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="<?php echo base_url();?>assets/plugins/input-mask/jquery.inputmask.extensions.js"></script>

<!-- daterangepicker
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>-->
<script src="<?php echo base_url();?>assets/plugins/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="<?php echo base_url();?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- bootstrap time picker -->
<script src="<?php echo base_url();?>assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url();?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="<?php echo base_url();?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url();?>assets/plugins/fastclick/fastclick.js"></script>
<!-- Data Table -->
<script src="<?php echo base_url();?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>assets/dist/js/app.min.js"></script>

<script src="<?php echo base_url();?>assets/plugins/bootstrap-editable/js/bootstrap-editable.min.js" type="text/javascript"></script>

<!-- Alertify -->
<script src="<?php echo base_url();?>assets/plugins/alertify/alertify.min.js"></script>
<!-- Sweet Alert -->
<script src="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Custom validation -->
<script src="<?php echo base_url();?>assets/custom/validate_master.js"></script>
</body>
</html>
