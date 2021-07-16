
<!DOCTYPE html> 
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ZANECO WMS | Log in</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="../dist/img/logo2-1.2x1.2.png"> 
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../dist/css/ionicons.min.css">
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="../plugins/toastr/toastr.min.css">  
</head>
<body class="hold-transition login-page">  

  <?php  session_start();  ?>

  <div class="login-box">
    <div class="login-logo">
      <div class="text-center">
        <img class="profile-user-img img-fluid img-circle" src="../dist/img/logo2-2x2.png" alt="User profile picture">
      </div>
      <a href="#"><b>ZANECO</b>WMS</a>
    </div>
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>        
        <form action="datahelpers/login_helper.php" method="post">            
          <div class="input-group mb-3">
            <input type="text" class="form-control" style="text-align: center;" id="username" name="username" placeholder="Username">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" style="text-align: center;" id="password" name="password" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" id="remember">
                <label for="remember">
                  Remember Me
                </label>
              </div>
            </div>
            <div class="col-4">
              <button type="submit" name="login" class="btn btn-primary btn-block">Sign In</button>
            </div>
          </div>
        </form>

        <div class="social-auth-links text-center mb-3">
          <?php 
          if(isset($_SESSION['error'])) {
            echo '<hr><br><label style="color: red">'.$_SESSION["error"].'</label>';
          }  
          ?>
        </div>
        <p class="mb-1">
          <!--a href="">I forgot my password</a-->
        </p>
        <p class="mb-0">
          <!--a href="" class="text-center">Register a new membership</a-->
        </p>
      </div>
    </div>
  </div>

  <script src="../plugins/jquery/jquery.min.js"></script>
  <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../dist/js/adminlte.min.js"></script>
  <script src="../plugins/sweetalert2/sweetalert2.min.js"></script>
  <script src="../plugins/toastr/toastr.min.js"></script> 

</body>
</html>
