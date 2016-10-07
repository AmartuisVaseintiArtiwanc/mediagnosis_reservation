<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Mediagnosis | Log in</title>
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
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/iCheck/square/blue.css">
    <!-- Alertify -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/alertify/alertify.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/alertify/themes/default.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Login</b> Admin</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>

    <form action="" method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" id="username" placeholder="Username" data-label="#err-username">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
          <span class="label label-danger" id="err-username"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" id="password" placeholder="Password" data-label="#err-password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          <span class="label label-danger" id="err-password"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox"> Remember Me
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" id="btn-login" class="btn btn-primary btn-block btn-flat">Login</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    <a href="#">I forgot my password</a><br>
    <a href="#" class="text-center">Register a new membership</a>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.0 -->
<script src="<?php echo base_url();?>assets/plugins/jQuery/jQuery-2.2.0.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url();?>assets/bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?php echo base_url();?>assets/plugins/iCheck/icheck.min.js"></script>
<!-- Alertify -->
<script src="<?php echo base_url();?>assets/plugins/alertify/alertify.min.js"></script>
<script src="<?php echo base_url();?>assets/custom/validate_master.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });

      function validate() {
          var err = 0;

          if (!$('#username').validateRequired()) {
              err++;
          }
          if (!$('#password').validateRequired()) {
              err++;
          }

          if (err != 0) {
              return false;
          } else {
              return true;
          }
      }

      $("#btn-login").click(function(){
          if(validate()){
              var formData = new FormData();
              formData.append("username", $("#username").val());
              formData.append("password", $("#password").val());

              $(this).saveData({
                  url		 : "<?php echo site_url('Login/validateLogin')?>",
                  data		 : formData,
                  locationHref : "<?php echo site_url('Welcome')?>",
                  hrefDuration : 100
              });
          }
          return false;
      });
  });
</script>
</body>
</html>
